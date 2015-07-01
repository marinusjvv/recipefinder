<?php
use MarinusJvv\Recipe\Recipes;

class RecipesTest extends PHPUnit_Framework_TestCase
{
    public function testBuild_GivenIncorrectFileName_ThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Recipe\Exceptions\RecipeNotFoundException');
        $recipes = new Recipes('no/such/location');
        $recipes->build();
    }

    public function testBuild_GivenInvalidJSONData_ThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Recipe\Exceptions\InvalidRecipiesException');
        $recipes = new Recipes(__DIR__ . '/data/bad_recipe_data.txt');
        $recipes->build();
    }

    public function testBuild_GivenInvalidRecipe_DoesntAddRecipe()
    {
        $recipes = new Recipes(__DIR__ . '/data/invalid_recipe_data.txt');
        $recipes->build();
        $this->assertEmpty($recipes->getRecipies());
    }

    public function testBuild_GivenValidData_BuildsRecipies()
    {
        $recipes = new Recipes(__DIR__ . '/data/recipe_input.txt');
        $recipes->build();
        $this->assertEquals($this->getExpectedRecipies(), $recipes->getRecipies());
    }

    private function getExpectedRecipies()
    {
        return array(
            0 => array(
                'name' => 'grilled cheese on toast',
                'ingredients' => array(
                    0 => array(
                        'item' => 'bread',
                        'amount' => '2',
                        'unit' => 'slices',
                    ),
                    1 => array(
                        'item' => 'cheese',
                        'amount' => '2',
                        'unit' => 'slices'
                    ),
                ),
            ),
            1 => array(
                'name' => 'salad sandwich',
                'ingredients' => array(
                    0 => array(
                        'item' => 'bread',
                        'amount' => '2',
                        'unit' => 'slices'
                    ),
                    1 => array(
                        'item' => 'mixed salad',
                        'amount' => '200',
                        'unit' => 'grams'
                    ),
                ),
            ),
        );
    }
}
