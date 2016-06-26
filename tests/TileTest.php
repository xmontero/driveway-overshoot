<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\PotentialValuesState;
use XaviMontero\DrivewayOvershoot\Tile;
use XaviMontero\DrivewayOvershoot\Value;

class TileTest extends \PHPUnit_Framework_TestCase
{
    private $tileCoordinates;
    private $sudokuMock;
    private $sut;

    protected function setUp()
    {
        $this->tileCoordinates = new Coordinates( 3, 5 );

        $this->sudokuMock = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\Sudoku' )
            ->setMethods( array( 'checkIncompatibility' ) )
            ->getMock();

        $this->sut = new Tile( $this->sudokuMock, $this->tileCoordinates );
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

    //-- Generic value wrapper --------------------------------------------//

    public function testHasNotValueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasValue() );
    }

    public function testHasValueAfterSettingAnInitialValue()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value( 4 ) );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testHasValueAfterSettingASolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new Value( 4 ) );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testHasNotValueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value( 4 ) );
        $sut->removeInitialValue();
        $sut->setSolutionValue( new Value( 7 ) );
        $sut->removeSolutionValue();
        $this->assertFalse( $sut->hasValue() );
    }

    public function testGetValueTrowsExceptionIfNotSet()
    {
        $this->expectException( \LogicException::class );
        $this->getSut()->getValue();
    }

    public function testGetValueReturnsInitialValue()
    {
        $sut = $this->getSut();

        $sut->setInitialValue( new Value( 4 ) );
        $this->assertTrue( $sut->getValue()->equals( new Value( 4 ) ) );
    }

    public function testGetValueReturnsSolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new Value( 4 ) );
        $this->assertTrue( $sut->getValue()->equals( new Value( 4 ) ) );
    }

    //-- Flagged as error -------------------------------------------------//

    public function testHasIncompatibleInitialValueCallsCallback()
    {
        $this->sudokuMock->expects( $this->once() )
            ->method( 'checkIncompatibility' )
            ->with( $this->tileCoordinates )
            ->willReturn( false );

        $this->getSut()->hasIncompatibleValue();
    }

    public function testHasNotIncompatibleValue()
    {
        $this->sudokuMock->method( 'checkIncompatibility' )->willReturn( false );

        $this->assertFalse( $this->getSut()->hasIncompatibleValue() );
    }

    public function testHasIncompatibleValue()
    {
        $this->sudokuMock->method( 'checkIncompatibility' )->willReturn( true );

        $this->assertTrue( $this->getSut()->hasIncompatibleValue() );
    }
}
