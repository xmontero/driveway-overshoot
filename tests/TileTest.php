<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\PotentialValuesState;
use XaviMontero\DrivewayOvershoot\Tile;
use XaviMontero\DrivewayOvershoot\Value;

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

    //-- State ------------------------------------------------------------//

    public function testAfterCreationIsEmpty()
    {
        $this->assertTrue( $this->getSut()->isEmpty() );
    }

    //-- Potential values -------------------------------------------------//

    public function testPotentialValuesIsOfProperClass()
    {
        $potentialValues = $this->getSut()->getPotentialValues();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\PotentialValues', $potentialValues );
    }

    public function testAfterInitializingIsNotEmpty()
    {
        $this->getSut()->setInitialValue( new Value( 4 ) );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }

    public function testAfterCreationHasAllPotentialValues()
    {
        $potentialValues = $this->getSut()->getPotentialValues();
        $this->assertEquals( PotentialValuesState::Full(), $potentialValues->getState() );
    }

    //-- Initial values ---------------------------------------------------//

    public function testHasInitialValue()
    {
        $this->assertFalse( $this->getSut()->hasInitialValue() );
        $this->getSut()->setInitialValue( new Value( 4 ) );
        $this->assertTrue( $this->getSut()->hasInitialValue() );
    }

    public function testGetInitialValueTrowsExceptionIfNotSet()
    {
        $this->expectException( \TypeError::class );
        $this->getSut()->getInitialValue();
    }

    public function testGetInitialValueReturnsSetValue()
    {
        $this->getSut()->setInitialValue( new Value( 4 ) );
        $this->assertTrue( $this->getSut()->getInitialValue()->equals( new Value( 4 ) ) );
    }

    //-- States -----------------------------------------------------------//

    public function testInitialStateIsEmptyCompatible()
    {
        //$state = $this->getSut()->getState();
        //$this->assertEquals( TileState::EmptyCompatible(), $state );
    }
}
