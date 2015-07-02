<?php
namespace MarinusJvv\Recipe;

class Finder
{
    /**
     * @var Fridge $fridge_file_location Fridge file location
     */
    private $fridge_file_location;

    /**
     * @var Recipes $recipe_file_location Recipes file location
     */
    private $recipe_file_location;

    /**
     * @param string $fridge_file_location Location of fridge file
     * @param string $recipe_file_location Location of recipes file
     */
    public function __construct($fridge_file_location, $recipe_file_location)
    {
        $this->fridge_file_location = $fridge_file_location;
        $this->recipe_file_location = $recipe_file_location;
    }

    /**
     * Returns tonights dinner plan
     *
     * @return string
     */
    public function findMeDinner()
    {
        $processor = new Processor(
            $this->getFilledFridge(),
            $this->getBuiltRecipes()
        );
        $available_recipes = $processor->getMeRecipes();
        return $this->processAvailableRecipes($available_recipes);
    }

    /**
     * Fills and returns fridge with it's ingredients
     *
     * @return Fridge
     */
    private function getFilledFridge()
    {
        $fridge = new Fridge($this->fridge_file_location);
        $fridge->fill();
        return $fridge;
    }

    /**
     * Builds and returns available recipes
     *
     * @return Recipes
     */
    private function getBuiltRecipes()
    {
        $recipes = new Recipes($this->recipe_file_location);
        $recipes->build();
        return $recipes;
    }  

    /**
     * Processes available recipes and returns dinner plans
     * 
     * @return string
     */
    private function processAvailableRecipes($available_recipes)
    {
        if (count($available_recipes) === 0) {
            return 'Order takeout';
        } else {
            $dinner = array_shift($available_recipes);
            return $dinner['name'];
        }
    }
}
