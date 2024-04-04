<?php
// Include Parsedown library
require_once __DIR__ . '/../assets/parsedown/Parsedown.php';

class Vtl_gen extends Trongate
{

    //used for pagination

    private $showSelectedDataTable;
    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);
// Function to check if daylight saving time is in effect
    function isDaylightSavingTime() {
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
        unset($_SESSION['selectedDataTable']);

        // Get a list of all tables
        $data['tables'] = $this->setupTablesForDropdown();
        // Construct file paths for markdown files
        $filepathIntro = __DIR__ . '/../assets/help/intro.md';
        $filepathCreateData = __DIR__ . '/../assets/help/createdata.md';
        $filepathShowData = __DIR__ . '/../assets/help/showdata.md';
        $filepathDeleteData = __DIR__ . '/../assets/help/deletedata.md';
        $filepathCreateIndex = __DIR__ . '/../assets/help/createindex.md';
        $filepathDeleteIndex = __DIR__ . '/../assets/help/deleteindex.md';
        $filepathExport = __DIR__ . '/../assets/help/export.md';

        // Initialize Parsedown
        $parsedown = new Parsedown();

        // Open markdown files
        $fileIntro = fopen($filepathIntro, 'r');
        $fileCreateData = fopen($filepathCreateData, 'r');
        $fileShowData = fopen($filepathShowData, 'r');
        $fileDeleteData = fopen($filepathDeleteData, 'r');
        $fileCreateIndex = fopen($filepathCreateIndex, 'r');
        $fileDeleteIndex = fopen($filepathDeleteIndex, 'r');
        $fileExport = fopen($filepathExport, 'r');

        // Read markdown content and parse it
        $markdownIntro = $parsedown->text(fread($fileIntro, filesize($filepathIntro)));
        $markdownCreateData = $parsedown->text(fread($fileCreateData, filesize($filepathCreateData)));
        $markdownShowData = $parsedown->text(fread($fileShowData, filesize($filepathShowData)));
        $markdownDeleteData = $parsedown->text(fread($fileDeleteData, filesize($filepathDeleteData)));
        $markdownCreateIndex = $parsedown->text(fread($fileCreateIndex, filesize($filepathCreateIndex)));
        $markdownDeleteIndex = $parsedown->text(fread($fileDeleteIndex, filesize($filepathDeleteIndex)));
        $markdownExport = $parsedown->text(fread($fileExport, filesize($filepathExport)));

        // Close markdown files
        fclose($fileIntro);
        fclose($fileCreateData);
        fclose($fileShowData);
        fclose($fileDeleteData);
        fclose($fileCreateIndex);
        fclose($fileDeleteIndex);
        fclose($fileExport);

        // Store parsed markdown content in data array
        $data['markdownIntro'] = $markdownIntro;
        $data['markdownCreateData'] = $markdownCreateData;
        $data['markdownShowData'] = $markdownShowData;
        $data['markdownDeleteData'] = $markdownDeleteData;
        $data['markdownCreateIndex'] = $markdownCreateIndex;
        $data['markdownDeleteIndex'] = $markdownDeleteIndex;
        $data['markdownExport'] = $markdownExport;

        // Get images for display
        $data['images']= $this -> getImagesForDisplay();


        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'vtl_gen';
        $this->template('public', $data);
    }
    // Function to get images for display
    private function getImagesForDisplay(): array {
        $basedir = APPPATH . 'modules/vtl_gen/assets/help/images/';
        $arrFilename = array();
        if ($handle = opendir($basedir)){
            while (false !== ($filename = readdir($handle))){
                if ($filename != "." && $filename != ".."){
                    array_push($arrFilename, $filename);
                }
            }
            closedir($handle);
        }
        return $arrFilename;
    }

    // Function to render delete index page
    public function deleteIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['indexInfo'] = $this->getAllTablesAndTheirIndexes();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deleteindex';
        $this->template('public', $data);
    }



    // Function to setup tables for dropdown
    private function setupTablesForDropdown(): array
    {
        $tables = $this->getAllTables();
        $starterArray = ['Select table...'];
        $tables = array_merge($starterArray, $tables);
        return $tables;
    }

    private function getAllTables(): array
    {
        $tables = [];
        $sql = 'SHOW TABLES';
        $column_name = 'Tables_in_' . DATABASE;
        $rows = $this->model->query($sql, 'array');
        foreach ($rows as $row) {

            $tables[] = $row[$column_name];
        }


        return $tables;
    }

    private function getAllTablesAndTheirIndexes(): array
    {
        $tablesAndIndexes = [];

        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $sql = 'SHOW INDEX FROM ' . $table;
            $indexes = $this->model->query($sql, 'array');

            $tableIndexInfo = [
                'table' => $table,
                'indexes' => $indexes,
            ];

            $tablesAndIndexes[] = $tableIndexInfo;
        }

        return $tablesAndIndexes;
    }

    public function createData(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createdata';
        $this->template('public', $data);
    }

    private function getAllTablesAndTheirColumnData(): array
    {
        $tablesAndColumns = [];

        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $sql = 'SHOW COLUMNS IN ' . $table;
            $columns = $this->model->query($sql, 'array');

            $tableInfo = [
                'table' => $table,
                'columns' => $columns,
            ];

            $tablesAndColumns[] = $tableInfo;
        }

        return $tablesAndColumns;
    }

    public function createIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createindex';
        $this->template('public', $data);
    }

    public function deleteData(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deletedata';
        $this->template('public', $data);
    }

    public function export(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'export';
        $this->template('public', $data);
    }
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

    private function setupTablesForDatabaseAdmin(): array
    {
        $tables = $this->getAllTables();
        $tables = array_merge( $tables);
        return $tables;
    }
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
        $rows = $this->model->get(target_tbl:  $selectedDataTable);


        $pagination_data['total_rows'] = count($rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'vtl_gen/showData';
        $pagination_data['record_name_plural'] =  $selectedDataTable;
        $pagination_data['include_showing_statement'] = true;



        $data['rows'] = $this -> _reduce_rows($rows);
        $data['pagination_data'] = $pagination_data;
        $data['selected_per_page'] =  $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;

        //finally pass this to a view.
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'showdata';
        $this->template('public', $data);
    }


    public function clearData(): void
    {
        // Retrieve raw POST data from the request body
        $rawPostData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $postData = json_decode($rawPostData, true);
        //var_dump($postData);
        // Extract relevant data from the decoded JSON
        $selectedTables = $postData['selectedTables'];
        $resetAutoIncrement = $postData['resetAutoIncrement'];



       if ($selectedTables != null && $selectedTables != "") {
           $responseText = '';
           $deletedTables = [];
           $failedTables = [];

           if ($resetAutoIncrement) {
               try{
                   $sql = 'nothing';
                   foreach ($selectedTables as $key => $selectedTable) {
                       switch ($selectedTable) {
                           case 'trongate_users':
                           case 'trongate_user_levels':
                           case 'trongate_administrators':
                               break;
                           default:
                               $sql = 'TRUNCATE TABLE ' . $selectedTable;
                               break;
                       }
                       try{
                           if ($sql != 'nothing')
                           {
                               $this->model->query($sql, '');

                               // If the query was successful, add the table to the list of deleted tables
                               $deletedTables[] = $selectedTable;
                           }
                       }
                       catch (Exception $e) {
                           // Handle the exception here, you can log it, display an error message, or take any other appropriate action
                           // In this example, we're just logging the error message
                           echo 'Error: ' . $e->getMessage();
                           // Add the table to the list of failed tables
                           $failedTables[] = $selectedTable;
                       }

                   }
               }
               catch (Exception $e) {
                   // If an exception was thrown outside of the foreach loop, handle it here
                   echo 'Error: ' . $e->getMessage();
                   $responseText .= 'Operation failed.' . $e;
               }
           }
           else {
               try {
                   foreach ($selectedTables as $key => $selectedTable) {

                       // Create our SQL statement here
                       $sql = 'DELETE FROM ' . $selectedTable;
                       switch ($selectedTable) {
                           case 'trongate_users':
                           case 'trongate_user_levels':
                           case 'trongate_administrators':
                               $sql .= ' Where id > 1';
                               break;
                           default:
                               break;
                       }
                       try {
                           // Enclose the query method in a try-catch block
                           $this->model->query($sql, '');

                           // If the query was successful, add the table to the list of deleted tables
                           $deletedTables[] = $selectedTable;
                       } catch (Exception $e) {
                           // Handle the exception here, you can log it, display an error message, or take any other appropriate action
                           // In this example, we're just logging the error message
                           echo 'Error: ' . $e->getMessage();
                           // Add the table to the list of failed tables
                           $failedTables[] = $selectedTable;
                       }
                   }

                   // If no exception was thrown, it means all queries were successful
                   $responseText .= 'Operation completed successfully.';
               } catch (Exception $e) {
                   // If an exception was thrown outside of the foreach loop, handle it here
                   echo 'Error: ' . $e->getMessage();
                   $responseText .= 'Operation failed.' . $e;
               }
           }
            // Append the list of deleted tables to the response text
           $responseText .= ' Deleted tables: ' . implode(', ', $deletedTables) . '.';
            // Append the list of failed tables to the response text
           $responseText .= ' Failed tables: ' . implode(', ', $failedTables) . '.';

            // Now $responseText contains the report for the whole operation
           echo $responseText;
       }
       else{ echo 'No Tables were selected';}

    }

    protected function extractBaseType($type): string
    {
        // Use a regular expression to match the base type
        if (preg_match('/^(\w+)(?:\(\d+\))?/', $type, $matches)) {
            return $matches[1];
        }
        return $type; // Return the original type if no match
    }

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

    function _reduce_rows(array $all_rows): array {
        $rows = [];
        $start_index = $this->_get_offset();
        $limit = $this->_get_limit();
        $end_index = $start_index + $limit;

        $count = -1;
        foreach ($all_rows as $row) {
            $count++;
            if (($count>=$start_index) && ($count<$end_index)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Get the limit for pagination.
     *
     * @return int Limit for pagination.
     */
    function _get_limit(): int {
        if (isset($_SESSION['selected_per_page'])) {
            $limit = $this->per_page_options[$_SESSION['selected_per_page']];
        } else {
            $limit = $this->default_limit;
        }

        return $limit;
    }

    /**
     * Get the offset for pagination.
     *
     * @return int Offset for pagination.
     */
    function _get_offset(): int {
        $page_num = (int) segment(3);

        if ($page_num>1) {
            $offset = ($page_num-1)*$this->_get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    function _get_selected_per_page() {
        if (!isset($_SESSION['selected_per_page'])) {
            $selected_per_page = $this->per_page_options[1];
        } else {
            $selected_per_page = $_SESSION['selected_per_page'];
        }

        return $selected_per_page;
    }

}