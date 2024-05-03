<?php
// Include Parsedown library
require_once __DIR__ . '/../assets/parsedown/Parsedown.php';

/**
 *
 */
class Vtl_gen extends Trongate
{

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
        unset($_SESSION['selectedDataTable']);

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
        $this->template('public', $data);
    }
    // Function to get images for display

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

    // Function to render delete index page

    /**
     * @return array
     */
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
        $this->template('public', $data);
    }

    // Function to setup tables for dropdown

    /**
     * @return void
     * @throws Exception
     */
    public function deleteIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['indexInfo'] = $this->getAllTablesAndTheirIndexes();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deleteindex';
        $this->template('public', $data);
    }

    /**
     * @return array
     */
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
        $this->template('public', $data);
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
            $columns = $this->model->query($sql, 'array');

            $tableInfo = [
                'table' => $table,
                'columns' => $columns,
            ];

            $tablesAndColumns[] = $tableInfo;
        }

        return $tablesAndColumns;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function createIndex(): void
    {
        $data['tables'] = $this->setupTablesForDropdown();
        $data['columnInfo'] = $this->getAllTablesAndTheirColumnData();
        $data['dropdownLabel'] = 'Tables in ' . DATABASE;
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'createindex';
        $this->template('public', $data);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function deleteData(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'deletedata';
        $this->template('public', $data);
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
     * @return void
     * @throws Exception
     */
    public function export(): void
    {
        $data['tables'] = $this->setupTablesForDatabaseAdmin();
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'export';
        $this->template('public', $data);
    }

    /**
     * @return void
     * @throws ReflectionException
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
        $rows = $this->model->get(target_tbl: $selectedDataTable);


        $pagination_data['total_rows'] = count($rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'vtl_gen/showData';
        $pagination_data['record_name_plural'] = $selectedDataTable;
        $pagination_data['include_showing_statement'] = true;


        $data['rows'] = $this->_reduce_rows($rows);
        $data['pagination_data'] = $pagination_data;
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;

        //finally pass this to a view.
        $data['view_module'] = 'vtl_gen';
        $data['view_file'] = 'showdata';
        $this->template('public', $data);
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