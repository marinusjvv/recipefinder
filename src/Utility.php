<?php
namespace MarinusJvv\Recipe;

class Utility
{
    public static function getInvalidParametersMessage()
    {
        return 'This program requires 2 arguments: The location of the Fridge CSV, followed by the location of the recipe JSON file';
    }
    
    public static function getInvalidFridgeFileMessage()
    {
        return 'Fridge file does not exist or is inaccessible';
    }
    
    public static function getInvalidRecipesFileMessage()
    {
        return 'Recipes file does not exist or is inaccessible';
    }
}
