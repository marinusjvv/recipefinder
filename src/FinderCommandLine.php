<?php 
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use MarinusJvv\Recipe\Finder;
use MarinusJvv\Recipe\Utility;
use MarinusJvv\Recipe\Exceptions as Exceptions;

/*
 * Runs script with arguments passed from command line
 */
$finder = new FinderCommandLine($argv);
echo $finder->getBestDinnerOption();

class FinderCommandLine
{
    /**
     * @var array $argv Input parameters
     */
    private $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    /**
     * Checks if parameters are ok and outputs dinner options
     *
     * @return string
     */
    public function getBestDinnerOption()
    {
        try {
            $this->checkParameters();
            $finder = new Finder($this->argv[1],$this->argv[2]);
            return $finder->findMeDinner();
        } catch (Exceptions\InvalidParameterCountException $e) {
            return Utility::getInvalidParametersMessage();
        } catch (Exceptions\FridgeNotFoundException $e) {
            return Utility::getInvalidFridgeFileMessage();
        } catch (Exceptions\RecipeNotFoundException $e) {
            return Utility::getInvalidRecipesFileMessage();
        }
    }

    /**
     * Checks the parameter count
     *
     * @throws MarinusJvv\Recipe\Exceptions\InvalidParameterCountException
     */
    private function checkParameters()
    {
        if (count($this->argv) !== 3) {
            throw new Exceptions\InvalidParameterCountException();
        }
    }
}