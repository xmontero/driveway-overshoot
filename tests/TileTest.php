<?php

namespace XaviMontero\DrivewayOvershoot;

class TileTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new Tile();
    }

    private function getSut() : Tile
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Tile', $this->getSut() );
    }

    public function testAfterCreationIsEmpty()
    {
        $this->assertTrue( $this->getSut()->isEmpty() );
    }

    public function testPotentialValuesIsOfProperClass()
    {
        $potentialValues = $this->getSut()->getPotentialValues();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\PotentialValues', $potentialValues );
    }

    /*
    public function testAfterCreationHasAllPotentialValues()
    {
        $this->getSut()->getPotentialValues()
    }
    */

    public function testAfterInitializingIsNotEmpty()
    {
        $this->getSut()->setInitialValue( 4 );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }
}
