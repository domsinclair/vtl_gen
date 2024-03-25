<?php
//
//class SettingsController
//{
//
//    protected $settings;
//
//    public function __construct($jsonFilePath = 'path/to/fakerstatements.json')
//    {
//        // Validate the existence of the JSON file
//        if (!file_exists($jsonFilePath)) {
//            throw new Exception("Settings file not found: $jsonFilePath");
//        }
//
//        // Read and parse the JSON file
//        $jsonContent = file_get_contents($jsonFilePath);
//        $this->settings = json_decode($jsonContent, true);
//
//        // Check if JSON decoding was successful
//        if ($this->settings === null) {
//            throw new Exception("Error decoding JSON file: $jsonFilePath");
//        }
//    }
//
//    public function getValueForKey($section, $key)
//    {
//        // Check if the section exists
//        if (!isset($this->settings[$section])) {
//            throw new Exception("Section not found: $section");
//        }
//
//        // Loop through the items in the section
//        foreach ($this->settings[$section] as $item) {
//            // Check if the key exists in the current item
//            if (isset($item[$key])) {
//                return $item[$key];
//            }
//        }
//
//        // If the key was not found in the specified section
//        throw new Exception("Key not found: $key");
//    }
//}
//
//// Example usage:
//$settingsController = new SettingsController();
//
//try {
//    $value = $settingsController->getValueForKey('faker', 'Key2');
//    echo "Value: $value";
//} catch (Exception $e) {
//    echo "Error: " . $e->getMessage();
//}
//
