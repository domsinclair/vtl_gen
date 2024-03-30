<?php
// Include Parsedown library
require_once __DIR__ . '/../assets/parsedown/Parsedown.php';

class Vtl_gen extends Trongate
{
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
//        if ($this->isDaylightSavingTime()) {
//            echo "Daylight saving time is in effect.";
//        } else {
//            echo "Daylight saving time is not in effect.";
//        }
//        $data['tables'] = $this->setupTablesForDropdown();
//        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
//        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        // Construct file paths for markdown files
        $filepathIntro = __DIR__ . '/../assets/help/intro.md';
        $filepathCreateData = __DIR__ . '/../assets/help/createdata.md';
        $filepathDeleteData = __DIR__ . '/../assets/help/deletedata.md';
        $filepathCreateIndex = __DIR__ . '/../assets/help/createindex.md';
        $filepathDeleteIndex = __DIR__ . '/../assets/help/deleteindex.md';
        $filepathExport = __DIR__ . '/../assets/help/export.md';

        // Initialize Parsedown
        $parsedown = new Parsedown();

        // Open markdown files
        $fileIntro = fopen($filepathIntro, 'r');
        $fileCreateData = fopen($filepathCreateData, 'r');
        $fileDeleteData = fopen($filepathDeleteData, 'r');
        $fileCreateIndex = fopen($filepathCreateIndex, 'r');
        $fileDeleteIndex = fopen($filepathDeleteIndex, 'r');
        $fileExport = fopen($filepathExport, 'r');

        // Read markdown content and parse it
        $markdownIntro = $parsedown->text(fread($fileIntro, filesize($filepathIntro)));
        $markdownCreateData = $parsedown->text(fread($fileCreateData, filesize($filepathCreateData)));
        $markdownDeleteData = $parsedown->text(fread($fileDeleteData, filesize($filepathDeleteData)));
        $markdownCreateIndex = $parsedown->text(fread($fileCreateIndex, filesize($filepathCreateIndex)));
        $markdownDeleteIndex = $parsedown->text(fread($fileDeleteIndex, filesize($filepathDeleteIndex)));
        $markdownExport = $parsedown->text(fread($fileExport, filesize($filepathExport)));

        // Close markdown files
        fclose($fileIntro);
        fclose($fileCreateData);
        fclose($fileDeleteData);
        fclose($fileCreateIndex);
        fclose($fileDeleteIndex);
        fclose($fileExport);

        // Store parsed markdown content in data array
        $data['markdownIntro'] = $markdownIntro;
        $data['markdownCreateData'] = $markdownCreateData;
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

    public function clearData(): void
    {
        // Retrieve raw POST data from the request body
        $rawPostData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $postData = json_decode($rawPostData, true);
        //var_dump($postData);
        // Extract relevant data from the decoded JSON
        $selectedTables = $postData['selectedTables'];

       if ($selectedTables != null && $selectedTables != "") {
           $responseText = '';
           $deletedTables = [];
           $failedTables = [];

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
               $responseText .= 'Operation failed.'.$e;
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

}