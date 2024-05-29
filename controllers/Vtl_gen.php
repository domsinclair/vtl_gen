<?php
// Include Parsedown library
require_once __DIR__ . '/../assets/parsedown/Parsedown.php';

/**
 *
 */
#[AllowDynamicProperties] class Vtl_gen extends Trongate
{
    private string $host = HOST;

    private string $dbname = DATABASE;

    private string $user = USER;

    private string $pass = PASSWORD;

    private $port = '';

    private $dbh;
    private $stmt;

    //used for pagination

    /**
     * @var
     */
    private $showSelectedDataTable;
    /**
     * @var int
     */
    private $default_limit = 20;
    /**
     * @var int[]
     */
    private $per_page_options = array(10, 20, 50, 100);


    public function __construct()
    {
        parent::__construct();

        // Now we need to be able to interact with the database
        if (DATABASE == '') {
            return;
        }

        $this->port = (defined('PORT') ? PORT : '3306');
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);

        } catch (PDOException $e) {
            $this->error = $e->getMessage();

            die();
        }
    }
// Function to check if daylight saving time is in effect

    /**
     * @return bool
     * @throws Exception
     */
    function isDaylightSavingTime()
    {
        $currentTime = time();
        $timezone = new DateTimeZone(date_default_timezone_get());
        $transition = $timezone->getTransitions($currentTime, $currentTime);

        foreach ($transition as $t) {
            if ($t['isdst'] == true) {
                return true;
            }
        }

        return false;
    }


    /**
     * Index function - renders the main page
     * @return void
     * @throws Exception
     */
    public function index(): void
    {
        $this->module('trongate_administrators');
        $token = $this->trongate_administrators->_make_sure_allowed();


        if (ENV != 'dev') {
            redirect(BASE_URL);
            die();
        } else {
            if ($token == '') {
                redirect(BASE_URL);
                die();
            }
        }
        unset($_SESSION['selectedDataTable']);

        // Define the list item HTML
        $listItemHTML = '<li>' . anchor('vtl_gen', '<img src="vtl_gen_module/help/images/vtlgen.svg"> Vtl Data Generator') . '</li>';

        // Path to the dynamic_nav.php file
        $filePath = APPPATH . 'templates/views/partials/admin/dynamic_nav.php';

        // Read the content of dynamic_nav.php
        $fileContent = file_get_contents($filePath);

        // Check if the list item already exists in the file
        if (strpos($fileContent, $listItemHTML) === false) {
            // If not, find the position to insert the new list item
            $pos = strpos($fileContent, '</ul>');
            if ($pos !== false) {
                // Insert the list item before the closing </ul> tag
                $newContent = substr_replace($fileContent, $listItemHTML, $pos, 0);

                // Write the modified content back to the file
                file_put_contents($filePath, $newContent);
            }
        }

        // Get a list of all tables
        $data['tables'] = $this->setupTablesForDropdown();
        // Construct file paths for markdown files
        $filepathIntro = __DIR__ . '/../assets/help/help.md';


        // Initialize Parsedown
        $parsedown = new Parsedown();


        // Open markdown files
        $fileIntro = fopen($filepathIntro, 'r');


        // Read markdown content and parse it
        $markdownIntro = $parsedown->text(fread($fileIntro, filesize($filepathIntro)));


        // Close markdown files
        fclose($fileIntro);


        // Store parsed markdown content in data array
        $data['markdownIntro'] = $markdownIntro;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'vtl_gen';
        $this->template('admin', $data);
    }


    /**
     * @return array
     */
    private function setupTablesForDropdown(): array
    {
        $tables = $this->getAllTables();
        $starterArray = ['Select table...'];
        $tables = array_merge($starterArray, $tables);
        return $tables;
    }


    /**
     * Get All Tables in the Database
     *
     * This function retrieves the names of all tables in the database.
     *
     * @return array An array containing the names of all tables in the database.
     */
    private function getAllTables(): array
    {
        $tables = [];
        $sql = 'SHOW TABLES';
        $column_name = 'Tables_in_' . DATABASE;
        $rows = $this->vtlQuery($sql, 'array');
        foreach ($rows as $row) {

            $tables[] = $row[$column_name];
        }


        return $tables;
    }


    /**
     * Execute SQL Query
     *
     * This function executes an SQL query and returns the result according to the specified return type.
     *
     * @param string      $sql          The SQL query to execute.
     * @param string|null $return_type  An optional parameter specifying the desired return type.
     *                                  Allowed values are 'object' for returning result objects,
     *                                  'array' for returning result arrays, or null for no result type.
     * @return mixed|null The result of the SQL query according to the specified return type,
     *                                  or null if no result type is specified.
     */
    public function vtlQuery(string $sql, ?string $return_type = null): mixed
    {

        $data = [];

        $this->VtlPrepareAndExecute($sql, $data);

        if (($return_type == 'object') || ($return_type == 'array')) {
            if ($return_type == 'object') {
                $query = $this->stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                $query = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $query;
        }

        // Return null for cases where no result type is expected
        return null;
    }


    /**
     * Prepare and Execute SQL Statement
     *
     * This function prepares and executes an SQL statement with optional data bindings.
     *
     * @param string $sql  The SQL statement to prepare and execute.
     * @param array  $data An optional array of data to bind to parameters in the SQL statement.
     *                     If named parameters are used in the SQL, $data should be an associative array
     *                     where keys correspond to parameter names. If positional parameters are used,
     *                     $data should be a sequential array where values correspond to parameter values.
     * @return bool True if the statement execution was successful, false otherwise.
     */
    public function VtlPrepareAndExecute(string $sql, array $data = []): bool
    {
        try {
            $this->stmt = $this->dbh->prepare($sql);

            if (isset($data[0])) { // unnamed data
                $success = $this->stmt->execute($data);
            } else {
                foreach ($data as $key => $value) {
                    $type = $this->vtlGetParamType($value);
                    $this->stmt->bindValue(":$key", $value, $type);
                }
                $success = $this->stmt->execute();
            }

            if (!$success) {
                throw new Exception("Execution failed: " . implode(", ", $this->stmt->errorInfo()));
            }

            return $success;
        } catch (Exception $e) {
            // Log or handle the error as necessary
            error_log($e->getMessage());
            return false;
        }
    }

    protected function vtlGetParamType(mixed $value): int
    {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            case is_float($value):
                return PDO::PARAM_STR; // PDO does not have a PARAM_FLOAT
            case is_resource($value): // For binary data
                return PDO::PARAM_LOB;
            default:
                return PDO::PARAM_STR;
        }
    }

    public function customiseFaker()
    {
        // Initialize Parsedown
        $parsedown = new Parsedown();

        // Construct file paths for markdown files
        $filepathCustomise = __DIR__ . '/../assets/help/customise.md';

        // Open markdown files
        $fileCustomise = fopen($filepathCustomise, 'r');


        // Read markdown content and parse it
        $markdownCustomise = $parsedown->text(fread($fileCustomise, filesize($filepathCustomise)));


        // Close markdown files
        fclose($fileCustomise);


        // Store parsed markdown content in data array
        $data['markdownCustomise'] = $markdownCustomise;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'customisefaker';
        $this->template('admin', $data);
    }

    /**
     * Delete Index
     *
     * This function prepares the data necessary for deleting indexes from a database table.
     * It gathers the available tables and their index information, sets up the view data,
     * and renders the appropriate template.
     *
     * @return void
     */
    public function deleteIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['indexInfo'] = $this->getAllTablesAndTheirIndexes();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deleteindex';
        $this->template('admin', $data);
    }

    /**
     * Get All Tables and Their Indexes
     *
     * This function retrieves all tables in the database and their corresponding index information.
     * It collects the index data for each table and returns it as an array.
     *
     * @return array An array containing tables and their index information.
     */
    private function getAllTablesAndTheirIndexes(): array
    {
        $tablesAndIndexes = [];

        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $sql = 'SHOW INDEX FROM ' . $table;
            $indexes = $this->vtlQuery($sql, 'array');

            $tableIndexInfo = [
                'table' => $table,
                'indexes' => $indexes,
            ];

            $tablesAndIndexes[] = $tableIndexInfo;
        }

        return $tablesAndIndexes;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function createData(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createdata';
        $this->template('admin', $data);
    }

    /**
     * @return array
     */
    private function getAllTablesAndTheirColumnData(): array
    {
        $tablesAndColumns = [];

        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $sql = 'SHOW COLUMNS IN ' . $table;
            $columns = $this->vtlQuery($sql, 'array');

            $tableInfo = [
                'table' => $table,
                'columns' => $columns,
            ];

            $tablesAndColumns[] = $tableInfo;
        }

        return $tablesAndColumns;
    }

    /**
     * Create Index
     *
     * This function prepares the data necessary for creating an index on a database table.
     * It gathers information about available tables and their columns, sets up the view data,
     * and renders the appropriate template.
     *
     * @return void
     */
    public function createIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createindex';
        $this->template('admin', $data);
    }

    /**
     * Delete Data
     *
     * This function prepares the data necessary for deleting entries from a database table.
     * It gathers the available tables for the database admin, sets up the view data,
     * and renders the appropriate template.
     *
     * @return void
     */
    public function deleteData(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deletedata';
        $this->template('admin', $data);
    }

    /**
     * @return array
     */
    private function setupTablesForDatabaseAdmin(): array
    {
        $tables = $this->getAllTables();
        $tables = array_merge($tables);
        return $tables;
    }

    /**
     * Export
     *
     * This function prepares the data necessary for exporting a database table.
     * It gathers the available tables for the database admin, sets up the view data,
     * and renders the appropriate template.
     *
     * @return void
     */
    public function export(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'export';
        $this->template('admin', $data);
    }

    /**
     * Create foreign key setup view.
     *
     * This method prepares data required for setting up foreign keys and renders the
     * corresponding view. It gathers information about the tables and their columns
     * to populate dropdowns and other elements in the view.
     *
     * @return void
     */
    public function createForeignKey(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        //$data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createforeignkey';
        $this->template('admin', $data);
    }

    public function showDeleteForeignKey(): void
    {
        $data['headline'] = 'Vtl Data Generator: Delete Foreign Key';
        $data['rows'] = $this->getForeignKeysFromDatabase();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deleteforeignkey';
        $this->template('admin', $data);
    }

    /**
     * @return mixed|null
     * @throws \ReflectionException
     */
    public function getForeignKeysFromDatabase(): mixed
    {
// Run the query to collect the information
        $sql = 'SELECT 
            CONCAT(table_name, \'.\', column_name) AS \'foreign key\', 
            CONCAT(referenced_table_name, \'.\', referenced_column_name) AS \'references\', 
            constraint_name AS \'constraint name\' 
        FROM 
            information_schema.key_column_usage 
        WHERE 
            referenced_table_name IS NOT NULL 
        AND 
            table_schema = \'' . DATABASE . '\'';

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $rows = $this->vtlQuery($sql, 'array');
        return $rows;
    }

    public function deleteForeignKey(): void
    {
        $rawPostData = file_get_contents('php://input');
        $postData = json_decode($rawPostData, true);

        if (isset($postData['selectedRows']) && is_array($postData['selectedRows'])) {
            $responseText = '';
            $deletedKeys = [];
            $failedKeys = [];

            foreach ($postData['selectedRows'] as $row) {
                if (isset($row['foreign key'], $row['constraint name'])) {
                    $foreignKey = $row['foreign key'];
                    $constraintName = $row['constraint name'];

                    // Extract the table name from the foreign key
                    list($tableName, $columnName) = explode('.', $foreignKey);

                    // SQL to drop the foreign key constraint
                    $sql = "ALTER TABLE $tableName DROP FOREIGN KEY $constraintName;";

                    try {
                        // Assuming vtlQuery is a method to execute your SQL queries
                        $this->vtl_gen->vtlQuery($sql, '');
                        $deletedKeys[] = $constraintName;
                    } catch (Exception $ex) {
                        echo 'Error: ' . $ex->getMessage();
                        // Add the foreign key to the list of failed keys
                        $failedKeys[] = $constraintName;
                    }
                } else {
                    $failedKeys[] = json_encode($row); // Include row data for debugging
                }
            }

            // Prepare response text
            if (!empty($deletedKeys)) {
                $responseText .= 'Deleted foreign keys: ' . implode(', ', $deletedKeys) . '. ';
            }
            if (!empty($failedKeys)) {
                $responseText .= 'Failed to delete foreign keys: ' . implode(', ', $failedKeys) . '.';
            }

            // Return the response as JSON
            echo json_encode(['status' => 'success', 'message' => $responseText]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No rows selected']);
        }
    }


    /**
     * Set foreign key constraint.
     *
     * This method reads JSON-encoded data from the request body to obtain the names of
     * the tables and fields involved in creating a foreign key constraint. It constructs
     * and executes an SQL query to add the foreign key constraint between the specified
     * fields of the two tables. The method then returns a JSON response indicating the
     * success or failure of the operation.
     *
     * @return void
     */
    public function setForeignKey()
    {
        $rawPostData = file_get_contents('php://input');
        $postData = json_decode($rawPostData, true);
        $table1 = $postData['table1'];
        $table2 = $postData['table2'];
        $selectedField1 = $postData['selectedField1'];
        $selectedField2 = $postData['selectedField2'];

        // Define the query
        $query = "ALTER TABLE `$table1` ADD CONSTRAINT `FK_{$table1}_{$table2}` FOREIGN KEY (`$selectedField1`) REFERENCES `$table2` (`$selectedField2`)";

        // Execute the query and handle the result
        try {
            $result = $this->vtlQuery($query);

            if ($result === FALSE) {
                $e = 'False result returned';
                throw new Exception($e);
            }

            $response = [
                'status' => 'success',
                'message' => 'Foreign key constraint created successfully'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Error creating foreign key constraint: ' . $e->getMessage()
            ];
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Show Data
     *
     * This function retrieves and displays data from a selected table. It extracts the table name
     * from the query parameters or session, ensures the user is authorized, retrieves the data,
     * and then displays it with pagination.
     *
     * @return void
     */
    public function showData(): void
    {

        // Extract the selected table from the query parameters

        //show table from Get request and set session variable on other pages
        if (isset($_GET['selectedTable'])) {
            $selectedDataTable = $_GET['selectedTable'];
            $_SESSION['selectedDataTable'] = $selectedDataTable;
        } else {
            $selectedDataTable = $_SESSION['selectedDataTable'];
        }

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $rows = $this->pdoGet(target_tbl: $selectedDataTable);
        $headline = 'Vtl Data Generator: Show Data';
        $noDataMessage = 'There is no data to display from the table ' . $selectedDataTable;
        $dateFormats = ['date' => 'DATE_SHORT', 'datetime' => 'DATETIME_SHORT'];
        $this->showRowData($rows, $headline, $noDataMessage, $dateFormats);

    }

    protected function pdoGet(?string $order_by = null, ?string $target_tbl = null, ?int $limit = null, int $offset = 0): array
    {
        if (is_null($target_tbl)) {
            throw new InvalidArgumentException('Target table cannot be null');
        }

        // Create the base SQL query
        $sql = "SELECT * FROM $target_tbl";

        // Add ORDER BY clause
        if (!is_null($order_by)) {
            $sql .= " ORDER BY $order_by";
        } else {
            // If no order_by is provided, order by the primary key
            $primary_key = $this->getPrimaryKey($target_tbl);
            if ($primary_key) {
                $sql .= " ORDER BY $primary_key";
            }
        }

        // Add LIMIT and OFFSET if provided
        if (!is_null($limit)) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        // Prepare the SQL statement
        $stmt = $this->dbh->prepare($sql);

        // Bind parameters if limit is provided
        if (!is_null($limit)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }


        // Execute the query
        $stmt->execute();

        // Fetch all rows
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);


        return $rows;
    }


    protected function getPrimaryKey(string $table): ?string
    {
        $sql = 'SHOW COLUMNS FROM ' . $table;
        $stmt = $this->dbh->query($sql);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            if ($column['Key'] === 'PRI') {
                return $column['Field'];
            }
        }

        return null;
    }

// Helper method to get the primary key of a table

    /**
     * Display row data with pagination and various configuration options.
     *
     * @param array  $rows           Array of rows to be displayed.
     * @param string $paginationRoot The root URL for pagination links.
     * @param string $selectedTable  The name of the selected table, used for display purposes.
     * @param string $headline       The headline text to be displayed.
     * @param string $noDataMessage  The message to be displayed when there is no data.
     *
     * @return void
     */
    private function showRowData(array $rows, string $headline, string $noDataMessage, array $dateFormats = ['date' => 'DATE_MED', 'datetime' => 'DATETIME_MED']): void
    {

        $data['rows'] = $rows;
        $data['headline'] = $headline;
        $data['noDataMessage'] = $noDataMessage;
        $data['dateFormats'] = $dateFormats; // Add this line to pass formats to the view
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'showdata';
        $this->template('admin', $data);
    }

    /**
     * @param array $all_rows
     * @return array
     */
    function _reduce_rows(array $all_rows): array
    {
        $rows = [];
        $start_index = $this->_get_offset();
        $limit = $this->_get_limit();
        $end_index = $start_index + $limit;

        $count = -1;
        foreach ($all_rows as $row) {
            $count++;
            if (($count >= $start_index) && ($count < $end_index)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Get the offset for pagination.
     *
     * @return int Offset for pagination.
     */
    function _get_offset(): int
    {
        $page_num = (int)segment(3);

        if ($page_num > 1) {
            $offset = ($page_num - 1) * $this->_get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    /**
     * Get the limit for pagination.
     *
     * @return int Limit for pagination.
     */
    function _get_limit(): int
    {
        if (isset($_SESSION['selected_per_page'])) {
            $limit = $this->per_page_options[$_SESSION['selected_per_page']];
        } else {
            $limit = $this->default_limit;
        }

        return $limit;
    }

    /**
     * @return int|mixed
     */
    function _get_selected_per_page()
    {
        if (!isset($_SESSION['selected_per_page'])) {
            $selected_per_page = $this->per_page_options[1];
        } else {
            $selected_per_page = $_SESSION['selected_per_page'];
        }

        return $selected_per_page;
    }

    public function fetchLatestPkValues()
    {
        $rows = $this->showLatestPkValues();
        $headline = 'Vtl Data Generator: Latest Primary Key Values for Tables';
        $noDataMessage = 'There are currently no tables in the database: ' . DATABASE . ' with any rows of data';
        $dateFormats = ['date' => 'DATE_SHORT', 'datetime' => 'DATETIME_SHORT'];
        $this->showRowData($rows, $headline, $noDataMessage, $dateFormats);
    }

    protected function showLatestPkValues()
    {
        // Get all tables
        $allTables = $this->setupTablesForDatabaseAdmin();
        $tablesWithData = [];

        foreach ($allTables as $table) {
            $primaryKeyField = $this->getPrimaryKeyField($table);
            if ($primaryKeyField && $this->tableHasRows($table)) {
                $latestPkValue = $this->getLatestPkValue($table, $primaryKeyField);
                $tablesWithData[] = [
                    'tableName' => $table,
                    'primaryKeyField' => $primaryKeyField,
                    'latestPkValue' => $latestPkValue
                ];
            }
        }

        return $tablesWithData;

    }

    /**
     * Get the primary key field for a table
     *
     * @param string $tableName
     * @return string|null
     */
    private function getPrimaryKeyField($tableName)
    {
        $query = "SHOW KEYS FROM `$tableName` WHERE Key_name = 'PRIMARY'";

        $stmt = $this->dbh->prepare($query);
        if (!$stmt) {
            $errorInfo = $this->dbh->errorInfo();
            throw new Exception("Error preparing query '$query': " . $errorInfo[2]);
        }

        $success = $stmt->execute();
        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Error executing query '$query': " . $errorInfo[2]);
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return $row['Column_name'];
    }

    /**
     * Check if a table has any rows
     *
     * @param string $tableName
     * @return bool
     */
    private function tableHasRows($tableName)
    {
        $query = "SELECT COUNT(*) as count FROM `$tableName`";

        try {
            $stmt = $this->dbh->query($query);
            if (!$stmt) {
                $errorInfo = $this->dbh->errorInfo();
                throw new Exception("Error executing query '$query': " . $errorInfo[2]);
            }
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'] > 0;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the latest primary key value for a table
     *
     * @param string $tableName
     * @param string $primaryKeyField
     * @return mixed
     */
    private function getLatestPkValue($tableName, $primaryKeyField)
    {
        $query = "SELECT `$primaryKeyField` FROM `$tableName` ORDER BY `$primaryKeyField` DESC LIMIT 1";

        try {
            $stmt = $this->dbh->query($query);
            if (!$stmt) {
                $errorInfo = $this->dbh->errorInfo();
                throw new Exception("Error executing query '$query': " . $errorInfo[2]);
            }
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row[$primaryKeyField] : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Drops tables from the database that are not referenced by any foreign key constraint.
     * Retrieves a list of tables from the database, identifies tables without foreign key constraints,
     * and presents them for deletion.
     *
     * @return void
     */
    public function dropDatatable(): void
    {

        $sql = 'SELECT 
           referenced_table_name AS \'referenced\' 
        FROM 
            information_schema.key_column_usage 
        WHERE 
            referenced_table_name IS NOT NULL 
        AND 
            table_schema = \'' . DATABASE . '\'';

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $referencedTables = $this->vtlQuery($sql, 'array');

        $allTables = $this->setupTablesForDatabaseAdmin();

        // Extracting only the table names from $referencedTables array
        $referencedTableNames = array_column($referencedTables, 'referenced');

        // Filtering out referenced tables from the list of all tables
        $tablesToDrop = array_diff($allTables, $referencedTableNames);

        // Now $tablesToDrop contains the list of tables that can be safely dropped

        $data['tables'] = $tablesToDrop;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'dropTable';
        $this->template('admin', $data);
    }

    /**
     * Show Foreign Keys
     *
     * This function retrieves foreign key information from the database and displays it.
     * It runs a SQL query to gather foreign key details, checks security permissions,
     * and then displays the results using a pagination system.
     *
     * @return void
     */
    public function showForeignKeys(): void
    {
        $rows = $this->getForeignKeysFromDatabase();
        $paginationRoot = 'vtl_gen/showForeignKeys';
        $selectedTable = 'Foreign Keys';
        $headline = 'Vtl Data Generator: Foreign Keys in Database';
        $noDataMessage = 'There are currently no foreign keys in the database: ' . DATABASE;
        $this->showRowData($rows, $paginationRoot, $selectedTable, $headline, $noDataMessage);

    }

    /**
     * @param $type
     * @return string
     */
    protected function extractBaseType($type): string
    {
        // Use a regular expression to match the base type
        if (preg_match('/^(\w+)(?:\(\d+\))?/', $type, $matches)) {
            return $matches[1];
        }
        return $type; // Return the original type if no match
    }

    private function addLimitOffset(string $sql, ?int $limit, ?int $offset): string
    {
        if ((is_numeric($limit)) && (is_numeric($offset))) {
            $limit_results = true;
            $sql .= " LIMIT $offset, $limit";
        }

        return $sql;
    }

    /**
     * @return array
     */
    private function getImagesForDisplay(): array
    {
        $basedir = APPPATH . 'modules/vtl_gen/assets/help/images/';
        $arrFilename = array();
        if ($handle = opendir($basedir)) {
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != "..") {
                    array_push($arrFilename, $filename);
                }
            }
            closedir($handle);
        }
        return $arrFilename;
    }

    /**
     * @return array
     */
    private function createExportScript(): array
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $database = DATABASE;
        $user = USER;
        $pass = PASSWORD;
        $host = HOST;
        $dir = dirname(__FILE__) . '/dump.sql';

        echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";

        exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);

        return $output;
    }

    /**
     * @param $section
     * @param $key
     * @return mixed
     * @throws Exception
     */
    private function getValueForKey($section, $key)
    {
        // Check if the section exists
        if (!isset($this->settings[$section])) {
            throw new Exception("Section not found: $section");
        }

        // Loop through the items in the section
        foreach ($this->settings[$section] as $item) {
            // Check if the key exists in the current item
            if (isset($item[$key])) {
                return $item[$key];
            }
        }

        // If the key was not found in the specified section
        throw new Exception("Key not found: $key");
    }

}