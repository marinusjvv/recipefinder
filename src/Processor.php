<?php
namespace MarinusJvv\Recipe;

use MarinusJvv\Recipe\Exceptions\UnUsableIngredientException;

class Processor
{
    /**
     * @var Fridge $file_name Fridge containing ingredients
     */
    private $fridge;

    /**
     * @var Recipes $file_name Recipes available
     */
    private $recipes;

    /**
     * @param Fridge $file_name Fridge containing ingredients
     * @param Recipes $recipes Recipes available
     */
    public function __construct(Fridge $fridge, Recipes $recipes)
    {
        $this->fridge = $fridge;
        $this->recipes = $recipes;
    }

    /**
     * Returns valid recipes
     *
     * @return array
     */
    public function getMeRecipes()
    {
        $useable_recipes = array();
        foreach ($this->recipes->getRecipies() as $recipe) {
            $closest_useby = 0;
            foreach ($recipe['ingredients'] as $name => $required_ingredient) {
                try {
                    $this->checkRecipeIngredientVersusFridge($name, $required_ingredient);
                } catch (UnUsableIngredientException $e) {
                    continue 2;
                }
                $closest_useby = $this->setClosestUseby($name, $closest_useby);
            }
            $useable_recipes[$closest_useby] = $this->addValidRecipe($recipe, $closest_useby);
        }
        return $useable_recipes;
    }

    /**
     * Validates an required ingredient to see if it is available
     *
     * @param string $name Name of ingredient 
     * @param array $required_ingredient Ingredient from recipe
     *
     * @throws MarinusJvv\Recipe\Exceptions\UnUsableIngredientException
     */
    private function checkRecipeIngredientVersusFridge($name, $required_ingredient)
    {
        $available_ingredients = $this->fridge->getIngredients();
        if ($this->doesIngredientExistInFridge($name, $available_ingredients) === false) {
            throw new UnUsableIngredientException();
        }
        if ($this->isIngredientPastUseby($available_ingredients[$name]['useby']) === true) {
            throw new UnUsableIngredientException();
        }
        if ($this->areThereEnoughIngredients($name, $available_ingredients, $required_ingredient) === false){
            throw new UnUsableIngredientException();
        }
    }

    /**
     * Checks if an ingredient is in the fridge
     *
     * @param string $name Name of ingredient 
     * @param array $available_ingredients Ingredients in fridge
     *
     * @return bool
     */
    private function doesIngredientExistInFridge($name, $available_ingredients)
    {
        return array_key_exists($name, $available_ingredients) === true;
    }

    /**
     * Checks if an ingredient is still fresh
     *
     * @param string $name useby date
     *
     * @return bool
     */
    private function isIngredientPastUseby($useby)
    {
        $useby = strtotime(str_replace('/', '-', $useby));
        return $useby < time();
    }

    /**
     * Checks if there are enough ingredients and the unit of measure matches
     *
     * @param string $name Name of ingredient 
     * @param array $available_ingredients Ingredients in fridge
     * @param array $required_ingredient Ingredient in recipe
     *
     * @return bool
     */
    private function areThereEnoughIngredients($name, $available_ingredients, $required_ingredient)
    {
        return $available_ingredients[$name]['amount'] >= (int)$required_ingredient['amount']
            && $available_ingredients[$name]['unit'] === $required_ingredient['unit'];
    }

    /**
     * Checks if current ingredient expires sooner
     *
     * @param string $name Name of ingredient 
     * @param int $closest_useby Current closest useby date
     *
     * @return int
     */
    private function setClosestUseby($name, $closest_useby)
    {
        $available_ingredients = $this->fridge->getIngredients();
        $current_useby = $available_ingredients[$name]['useby'];
        return ($current_useby > $closest_useby)
            ? $current_useby
            : $closest_useby;
    }

    /**
     * Returns formatted recipe
     *
     * @param array $recipe Valid recipe
     * @param int $closest_useby Current closest useby date
     *
     * @return array
     */
    private function addValidRecipe($recipe, $closest_useby)
    {
        return  array(
            'name' => $recipe['name'],
            'closest_useby' => $closest_useby,
            'ingredients' => $recipe['ingredients'],
        );
    }
}
