<?php
use MarinusJvv\Recipe\Finder;

class FinderTest extends PHPUnit_Framework_TestCase
{
    public function testSetupWorks()
    {
        $finder = new Finder();
        $this->assertTrue($finder->returnTrue());
    }
}
