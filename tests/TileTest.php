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

    public function testHasNotInitialValueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasInitialValue() );
    }

    public function testHasInitialValueAfterSettingAnInitialValue()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value( 4 ) );
        $this->assertTrue( $sut->hasInitialValue() );
    }

    public function testHasNotInitialValueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value( 4 ) );
        $sut->removeInitialValue();
        $this->assertFalse( $sut->hasInitialValue() );
    }

    public function testGetInitialValueTrowsExceptionIfNotSet()
    {
        $this->expectException( \LogicException::class );
        $this->getSut()->getInitialValue();
    }

    public function testGetInitialValueReturnsSetValue()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value(4) );
        $this->assertTrue( $sut->getInitialValue()->equals( new Value(4) ) );
    }

    //-- Solution values --------------------------------------------------//

    public function testHasNotSolutionValueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasSolutionValue() );
    }

    public function testHasSolutionValueAfterSettingASolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new Value( 4 ) );
        $this->assertTrue( $sut->hasSolutionValue() );
    }

    public function testHasNotSolutionValueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new Value( 4 ) );
        $sut->removeSolutionValue();
        $this->assertFalse( $sut->hasSolutionValue() );
    }

    public function testGetSolutionValueTrowsExceptionIfNotSet()
    {
        $this->expectException( \LogicException::class );
        $this->getSut()->getSolutionValue();
    }

    public function testGetSolutionValueReturnsSetValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new Value( 7 ) );
        $this->assertTrue( $sut->getSolutionValue()->equals( new Value( 7 ) ) );
    }

    //-- Initial and solution values interaction --------------------------//

    public function testSetSolutionValueThrowsExceptionIfInitialValueIsSet()
    {
        $sut = $this->getSut();

        $this->expectException( \LogicException::class );
        $sut->setInitialValue( new Value( 5 ) );
        $sut->setSolutionValue( new Value( 5 ) );
    }

    public function testSetInitialValueThrowsExceptionIfSolutionValueIsSet()
    {
        $sut = $this->getSut();

        $this->expectException( \LogicException::class );
        $sut->setSolutionValue( new Value( 5 ) );
        $sut->setInitialValue( new Value( 5 ) );
    }
}
