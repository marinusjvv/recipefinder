<?php
namespace MarinusJvv\Recipe;

use MarinusJvv\Recipe\Exceptions as Exceptions;

class Recipes
{
    /**
     * @var string $file_name Location of JSON file
     */
    private $file_name;

    /**
     * @var array $recipes List of valid recipes
     */
    private $recipes = array();

    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Adds all valid recipies to the list
     */
    public function build()
    {
        $recipes = $this->getRecipesDataFromFile();
        foreach ($recipes as $recipe) {
            if (array_key_exists('name', $recipe) === false
                || array_key_exists('ingredients', $recipe) === false) {
                continue;
            }
            try {
                $this->validateIngredients($recipe['ingredients']);
            } catch (Exceptions\InvalidIngredientException $e) {
                continue;
            } catch (Exceptions\NoIngredientsForRecipe $e) {
                continue;
            }
            $this->addRecipeToList($recipe);
        }
    }

    private function getRecipesDataFromFile()
    {
        if (is_readable($this->file_name) === false) {
            throw new Exceptions\RecipeNotFoundException();
        }
        $file_data = file_get_contents($this->file_name);
        $json_data = json_decode($file_data, true);
        if (empty($json_data) === true) {
            throw new Exceptions\InvalidRecipiesException();
        }
        return $json_data;
    }

    private function validateIngredients($ingredients)
    {
        if (count($ingredients) === 0) {
            throw new Exceptions\NoIngredientsForRecipe();
        }
        foreach ($ingredients as $ingredient) {
            if (count($ingredient) !== 3) {
                throw new Exceptions\InvalidIngredientException();
            }
            $validUnits = array('of', 'grams', 'ml', 'slices');
            if (in_array($ingredient['unit'], $validUnits) === false) {
                throw new Exceptions\InvalidIngredientException();
            }
        }
    }

    private function addRecipeToList($recipe)
    {
        $this->recipes[] = $recipe;
    }

    public function getRecipies()
    {
        return $this->recipes;
    }
}
