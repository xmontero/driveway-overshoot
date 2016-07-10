<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\PotentialValuesState;
use XaviMontero\DrivewayOvershoot\Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{
    private $cellCoordinates;
    private $sudokuMock;
    private $sut;

    protected function setUp()
    {
        $this->cellCoordinates = new Coordinates( new OneToNineValue( 3 ), new OneToNineValue( 5 ) );

        $this->sudokuMock = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\Sudoku' )
            ->setMethods( [ 'checkIncompatibility' ] )
            ->getMock();

        $this->sut = new Cell( $this->sudokuMock, $this->cellCoordinates );
    }

    private function getSut() : Cell
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Cell', $this->getSut() );
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

    public function testAfterSettingClueIsNotEmpty()
    {
        $this->getSut()->setClue( new OneToNineValue( 4 ) );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }

    public function testAfterCreationHasAllPotentialValues()
    {
        $potentialValues = $this->getSut()->getPotentialValues();
        $this->assertEquals( PotentialValuesState::Full(), $potentialValues->getState() );
    }

    //-- Clues ------------------------------------------------------------//

    public function testHasNotClueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasClue() );
    }

    public function testHasClueAfterSettingAnClue()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->hasClue() );
    }

    public function testHasNotClueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $sut->removeClue();
        $this->assertFalse( $sut->hasClue() );
    }

    public function testGetClueTrowsExceptionIfNotSet()
    {
        $this->expectException( \LogicException::class );
        $this->getSut()->getClue();
    }

    public function testGetClueReturnsSetValue()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->getClue()->equals( new OneToNineValue( 4 ) ) );
    }

    //-- Solution values --------------------------------------------------//

    public function testHasNotSolutionValueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasSolutionValue() );
    }

    public function testHasSolutionValueAfterSettingASolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->hasSolutionValue() );
    }

    public function testHasNotSolutionValueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new OneToNineValue( 4 ) );
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

        $sut->setSolutionValue( new OneToNineValue( 7 ) );
        $this->assertTrue( $sut->getSolutionValue()->equals( new OneToNineValue( 7 ) ) );
    }

    //-- Clue and solution values interaction -----------------------------//

    public function testSetSolutionValueThrowsExceptionIfClueIsSet()
    {
        $sut = $this->getSut();

        $this->expectException( \LogicException::class );
        $sut->setClue( new OneToNineValue( 5 ) );
        $sut->setSolutionValue( new OneToNineValue( 5 ) );
    }

    public function testSetClueThrowsExceptionIfSolutionValueIsSet()
    {
        $sut = $this->getSut();

        $this->expectException( \LogicException::class );
        $sut->setSolutionValue( new OneToNineValue( 5 ) );
        $sut->setClue( new OneToNineValue( 5 ) );
    }

    //-- Generic value wrapper --------------------------------------------//

    public function testHasNotValueAfterCreation()
    {
        $this->assertFalse( $this->getSut()->hasValue() );
    }

    public function testHasValueAfterSettingAnClue()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testHasValueAfterSettingASolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testHasNotValueAfterRemoval()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $sut->removeClue();
        $sut->setSolutionValue( new OneToNineValue( 7 ) );
        $sut->removeSolutionValue();
        $this->assertFalse( $sut->hasValue() );
    }

    public function testGetValueTrowsExceptionIfNotSet()
    {
        $this->expectException( \LogicException::class );
        $this->getSut()->getValue();
    }

    public function testGetValueReturnsClue()
    {
        $sut = $this->getSut();

        $sut->setClue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->getValue()->equals( new OneToNineValue( 4 ) ) );
    }

    public function testGetValueReturnsSolutionValue()
    {
        $sut = $this->getSut();

        $sut->setSolutionValue( new OneToNineValue( 4 ) );
        $this->assertTrue( $sut->getValue()->equals( new OneToNineValue( 4 ) ) );
    }

    //-- Flagged as error -------------------------------------------------//

    public function testHasIncompatibleClueCallsCallback()
    {
        $this->sudokuMock->expects( $this->once() )
            ->method( 'checkIncompatibility' )
            ->with( $this->cellCoordinates )
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

    //-- Get coordinates --------------------------------------------------//

    /**
     * @dataProvider getCoordinatesProvider
     */
    public function testGetCoordinates( $x, $y )
    {
        $cellCoordinates = new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) );
        $sut = new Cell( $this->sudokuMock, $cellCoordinates );

        $this->assertEquals( $cellCoordinates, $sut->getCoordinates() );
    }

    public function getCoordinatesProvider()
    {
        return
            [
                [ 3, 8 ],
                [ 1, 7 ],
            ];
    }
}
