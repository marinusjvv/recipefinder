<?php
use MarinusJvv\Recipe\Fridge;

class FridgeTest extends PHPUnit_Framework_TestCase
{
    public function testfill_GivenIncorrectFileName_ThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Recipe\Exceptions\FridgeNotFoundException');
        $fridge = new Fridge('no/such/location');
        $fridge->fill();
    }

    public function testFill_GivenInvalidIngredientUseBy_DoesntAddIngredient()
    {
        $fridge = new Fridge(__DIR__ . '/data/invalid_ingredients.csv');
        $fridge->fill();
        $this->assertEmpty($fridge->getIngredients());
    }

    public function testFill_GivenValidFile_FillsFridge()
    {
        $fridge = new Fridge(__DIR__ . '/data/fridge_input.csv');
        $fridge->fill();
        $this->assertEquals($this->getExpectedFilledFridge(), $fridge->getIngredients());
    }

    private function getExpectedFilledFridge()
    {
        return array(
            'bread' => array(
                'item' => 'bread',
                'amount' => 10,
                'unit' => 'slices',
                'useby' => '25/12/2016',
            ),
            'cheese' => array(
                'item' => 'cheese',
                'amount' => 10,
                'unit' => 'slices',
                'useby' => '25/12/2016',
            ),
            'butter' => array(
                'item' => 'butter',
                'amount' => 250,
                'unit' => 'grams',
                'useby' => '25/12/2016',
            ),
            'peanut butter' => array(
                'item' => 'peanut butter',
                'amount' => 250,
                'unit' => 'grams',
                'useby' => '2/12/2016',
            ),
            'mixed salad' => array(
                'item' => 'mixed salad',
                'amount' => 500,
                'unit' => 'grams',
                'useby' => '26/12/2015',
            ),
        );
    }
}
