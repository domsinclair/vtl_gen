<?php
require_once __DIR__ . '/../assets/parsedown/Parsedown.php';

class Vtl_gen extends Trongate
{

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
     * @return void
     * @throws Exception
     */
    public function index()
    {
//        if ($this->isDaylightSavingTime()) {
//            echo "Daylight saving time is in effect.";
//        } else {
//            echo "Daylight saving time is not in effect.";
//        }
//        $data['tables'] = $this->setupTablesForDropdown();
//        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
//        $data['dropdownLabel'] = 'Tables in ' . DATABASE;

        $filepathIntro = __DIR__ . '/../assets/help/intro.md';
        $filepathCreateData = __DIR__ . '/../assets/help/createdata.md';
        $filepathDeleteData = __DIR__ . '/../assets/help/deletedata.md';
        $filepathCreateIndex = __DIR__ . '/../assets/help/createindex.md';
        $filepathDeleteIndex = __DIR__ . '/../assets/help/deleteindex.md';
        $parsedown = new Parsedown();
        $fileIntro = fopen($filepathIntro, 'r');
        $fileCreateData = fopen($filepathCreateData, 'r');
        $fileDeleteData = fopen($filepathDeleteData, 'r');
        $fileCreateIndex = fopen($filepathCreateIndex, 'r');
        $fileDeleteIndex = fopen($filepathDeleteIndex, 'r');
        $markdownIntro = $parsedown->text(fread($fileIntro, filesize($filepathIntro)));
        $markdownCreateData = $parsedown->text(fread($fileCreateData, filesize($filepathCreateData)));
        $markdownDeleteData = $parsedown->text(fread($fileDeleteData, filesize($filepathDeleteData)));
        $markdownCreateIndex = $parsedown->text(fread($fileCreateIndex, filesize($filepathCreateIndex)));
        $markdownDeleteIndex = $parsedown->text(fread($fileDeleteIndex, filesize($filepathDeleteIndex)));
        $data['markdownIntro'] = $markdownIntro;
        $data['markdownCreateData'] = $markdownCreateData;
        $data['markdownDeleteData'] = $markdownDeleteData;
        $data['markdownCreateIndex'] = $markdownCreateIndex;
        $data['markdownDeleteIndex'] = $markdownDeleteIndex;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'vtl_gen';
        $this->template('public', $data);




    }

    public function deleteIndex(){
//        $sql = 'SHOW INDEX FROM trongate_pages';
//        $result = $this->model->query($sql,'array');
//        $data['result'] = $result;
        $data['tables'] = $this->setupTablesForDropdown();
        $data['indexInfo'] = $this->getAllTablesAndTheirIndexes();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deleteindex';
        $this->template('public', $data);
    }

    private function setupTablesForDropdown()
    {
        $tables = $this->getAllTables();
        $starterArray = ['Select table...'];
        $tables = array_merge($starterArray, $tables);
        return $tables;
    }

    private function getAllTables()
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

    private function getAllTablesAndTheirIndexes()
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

    public function createData(){
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createdata';
        $this->template('public', $data);
    }

    private function getAllTablesAndTheirColumnData()
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

    public function createIndex(){
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createindex';
        $this->template('public', $data);
    }

    public function deleteData(){
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deletedata';
        $this->template('public', $data);
    }

    private function setupTablesForDatabaseAdmin()
    {
        $tables = $this->getAllTables();
        $tables = array_merge( $tables);
        return $tables;
    }

    public function clearData(){
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

    protected function extractBaseType($type)
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