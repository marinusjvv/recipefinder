<?php

use MarinusJvv\Recipe\Utility;

class FinderCommandLineTest extends PHPUnit_Framework_TestCase
{

    public function testFinderCommandLine_GivenCorrectData_ReturnsDinnerMessage()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php ' . __DIR__ . '/data/fridge_input_different_dates.csv ' . __DIR__ . '/data/recipe_two_valid.txt', $output);
        $this->assertEquals(
            'peanut butter sandwich',
            $output[0]
        );
    }

    public function testFinderCommandLine_GivenMisingParameters_ReturnsFailureMessage()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php', $output);
        $this->assertEquals(
            Utility::getInvalidParametersMessage(),
            $output[0]
        );
    }

    public function testFinderCommandLine_GivenInvalidFridgeFile_ReturnsFailureMessage()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php ' . __DIR__ . '/data/not_valid ' . __DIR__ . '/data/recipe_two_valid.txt', $output);
        $this->assertEquals(
            Utility::getInvalidFridgeFileMessage(),
            $output[0]
        );
    }

    public function testFinderCommandLine_GivenInvalidRecipesFile_ReturnsFailureMessage()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php ' . __DIR__ . '/data/fridge_input_different_dates.csv ' . __DIR__ . '/data/not_valid', $output);
        $this->assertEquals(
            Utility::getInvalidRecipesFileMessage(),
            $output[0]
        );
    }
}