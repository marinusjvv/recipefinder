<?php
use MarinusJvv\Recipe\Finder;

class FinderTest extends PHPUnit_Framework_TestCase
{
    public function testfindMeDinner_ReturnsRecipeWithEarliestUseby()
    {
        $finder = new Finder(
            __DIR__ . '/data/fridge_input_different_dates.csv',
            __DIR__ . '/data/recipe_two_valid.txt'
        );
        $this->assertEquals('peanut butter sandwich', $finder->findMeDinner());
    }

    public function testfindMeDinner_GivenNoDinner_ReturnsOrderTakeout()
    {
        $finder = new Finder(
            __DIR__ . '/data/fridge_input_different_dates.csv',
           __DIR__ . '/data/recipe_not_enough_ingredients.txt'
        );
        $this->assertEquals('Order takeout', $finder->findMeDinner());
    }
}
