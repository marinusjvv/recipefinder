<?php
use MarinusJvv\Recipe\Processor;
use MarinusJvv\Recipe\Fridge;
use MarinusJvv\Recipe\Recipes;

class ProcessorTest extends PHPUnit_Framework_TestCase
{
    public function testSetupWorks()
    {
        $recipes = new Recipes(__DIR__ . '/data/recipe_input.txt');
        $recipes->build();
        $fridge = new Fridge(__DIR__ . '/data/fridge_input.csv');
        $fridge->fill();
        $processor = new Processor($fridge, $recipes);
        $returned = $processor->getMeRecipes();
        $actual = array_pop($returned);
        $this->assertEquals('salad sandwich', $actual['name']);
    }

    public function testGetMeRecipes_GivenTwoValidRecipes_ReturnsBoth()
    {
        $recipes = new Recipes(__DIR__ . '/data/recipe_two_valid.txt');
        $recipes->build();
        $fridge = new Fridge(__DIR__ . '/data/fridge_input_different_dates.csv');
        $fridge->fill();
        $processor = new Processor($fridge, $recipes);
        $returned = $processor->getMeRecipes();
        $actual = array_pop($returned);
        $this->assertEquals('peanut butter sandwich', $actual['name']);
        $actual = array_pop($returned);
        $this->assertEquals('grilled cheese on toast', $actual['name']);
    }

    public function testGetMeRecipes_GivenTooLittleIngredients_ReturnsNoRecipes()
    {
        $recipes = new Recipes(__DIR__ . '/data/recipe_not_enough_ingredients.txt');
        $recipes->build();
        $fridge = new Fridge(__DIR__ . '/data/fridge_input.csv');
        $fridge->fill();
        $processor = new Processor($fridge, $recipes);
        $this->assertEmpty($processor->getMeRecipes());
    }

    public function testGetMeRecipes_GivenPastUseByIngredients_ReturnsNoRecipes()
    {
        $recipes = new Recipes(__DIR__ . '/data/recipe_ingredient_past_useby.txt');
        $recipes->build();
        $fridge = new Fridge(__DIR__ . '/data/fridge_expired_peanut_butter.csv');
        $fridge->fill();
        $processor = new Processor($fridge, $recipes);
        $this->assertEmpty($processor->getMeRecipes());
    }
}
