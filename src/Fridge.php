<?php
namespace MarinusJvv\Recipe;

use MarinusJvv\Recipe\Exceptions as Exceptions;

class Fridge
{
    /**
     * @var string $file_name Location of CSV file
     */
    private $file_name;

    /**
     * @var array $ingredients List of valid ingredients
     */
    private $ingredients = array();

    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Adds all valid ingredients into the fridge
     */
    public function fill()
    {
        $handle = $this->getFileHandle();
        while (($ingredient = fgetcsv($handle)) !== false) {
            try {
                $this->validateIngredient($ingredient);
            } catch (Exceptions\InvalidIngredientException $e) {
                continue;
            }
            $this->addIngredientToFridge($ingredient);
        }
    }

    /**
     * Gets handle for file containing CSV
     *
     * @throws MarinusJvv\Recipe\Exceptions\FridgeNotFoundException
     *
     * @return Resource
     */
    private function getFileHandle()
    {
        if (is_readable($this->file_name) === false) {
            throw new Exceptions\FridgeNotFoundException();
        }
        return fopen($this->file_name, 'r');
    }

    /**
     * Validates if an ingredient is valid.
     *
     * @var array $ingredient Ingredient to be checked
     *
     * @throws MarinusJvv\Recipe\Exceptions\InvalidIngredientException
     */
    private function validateIngredient($ingredient)
    {
        if (count($ingredient) !== 4) {
            throw new Exceptions\InvalidIngredientException();
        }
        $date = $ingredient[3];
        $date = explode('/', $date);
        if (checkdate($date[1], $date[0], $date[2]) !== true) {
            throw new Exceptions\InvalidIngredientException();
        }
        $validUnits = array('of', 'grams', 'ml', 'slices');
        if (in_array($ingredient[2], $validUnits) === false) {
            throw new Exceptions\InvalidIngredientException();
        }
    }

    /**
     * Adds ingredient to the fridge.
     *
     * @var array $ingredient Ingredient to be added
     */
    private function addIngredientToFridge($ingredient)
    {
        $this->ingredients[] = array(
            'item' => $ingredient[0],
            'amount' => (int)$ingredient[1],
            'unit' => $ingredient[2],
            'useby' => $ingredient[3],
        );
    }

    /**
     * Returns all fridge ingredients
     *
     * @return array
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }
}
