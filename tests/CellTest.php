<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\CandidatesState;
use XaviMontero\DrivewayOvershoot\Cell;
use XaviMontero\DrivewayOvershoot\Tests\Helpers\CandidateKiller;

class CellTest extends \PHPUnit_Framework_TestCase
{
    private $cellCoordinates;
    private $sudokuMock;

    protected function setUp()
    {
        $this->cellCoordinates = new Coordinates( new OneToNineValue( 3 ), new OneToNineValue( 5 ) );

        $this->sudokuMock = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\SudokuGrid' )
            ->disableOriginalConstructor()
            ->setMethods( [ 'checkIncompatibility' ] )
            ->getMock();
    }

    //-- Creation ---------------------------------------------------------//

    public function testCreationIsOfProperClass()
    {
        $sut = $this->getSutWithoutClue();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Cell', $sut );
    }

    //-- Clues ------------------------------------------------------------//

    public function testItHasNotClueAfterCreationIfNoClueIsSet()
    {
        $sut = $this->getSutWithoutClue();
        $this->assertFalse( $sut->hasClue() );
    }

    public function testItHasNotValueAfterCreationIfNoClueIsSet()
    {
        $sut = $this->getSutWithoutClue();
        $this->assertFalse( $sut->hasValue() );
    }

    public function testItHasClueAfterCreationIfClueIsSet()
    {
        $sut = $this->getSutWithClue( 4 );
        $this->assertTrue( $sut->hasClue() );
    }

    public function testItHasValueAfterCreationIfClueIsSet()
    {
        $sut = $this->getSutWithClue( 4 );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testGetClueTrowsExceptionIfNotSet()
    {
        $sut = $this->getSutWithoutClue();
        $this->expectException( \LogicException::class );
        $sut->getClue();
    }

    public function testGetClueReturnsSetValue()
    {
        $sut = $this->getSutWithClue( 4 );
        $this->assertTrue( $sut->getClue()->equals( new OneToNineValue( 4 ) ) );
    }

    //-- Candidates -------------------------------------------------------//

    public function testCandidatesIsOfProperClass()
    {
        $sut = $this->getSutWithoutClue();
        $candidates = $sut->getCandidates();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Candidates', $candidates );
    }

    public function testAfterCreationHasAllCandidates()
    {
        $sut = $this->getSutWithoutClue();
        $candidates = $sut->getCandidates();
        $this->assertEquals( CandidatesState::Full(), $candidates->getState() );
    }

    public function testEditedCandidatesAreAccessibleFromAFutureCallToGetCandidates()
    {
        $killedValue = new OneToNineValue( 7 );

        $sut = $this->getSutWithoutClue();

        $candidates1 = $sut->getCandidates();
        $this->assertTrue( $candidates1->isOption( $killedValue ) );
        $candidates1->killOption( $killedValue );
        $this->assertFalse( $candidates1->isOption( $killedValue ) );

        $candidates2 = $sut->getCandidates();
        $this->assertFalse( $candidates2->isOption( $killedValue ) );
    }

    //-- Solution values --------------------------------------------------//

    public function testHasNotSolutionValueAfterCreation()
    {
        $sut = $this->getSutWithoutClue();
        $this->assertFalse( $sut->hasSolutionValue() );
    }

    public function testHasSolutionValueAfterSettingASolutionValue()
    {
        $sut = $this->getSutWithoutClue();
        CandidateKiller::killAllOptionsButSolutionFromCell( 4, $sut );
        $sut->setSolutionFromSingleCandidateIfPossible();

        $this->assertTrue( $sut->hasSolutionValue() );
    }

    public function testGetSolutionValueTrowsExceptionIfNotSet()
    {
        $sut = $this->getSutWithoutClue();
        $this->expectException( \LogicException::class );
        $sut->getSolutionValue();
    }

    public function testGetSolutionValueReturnsSetValue()
    {
        $sut = $this->getSutWithoutClue();
        CandidateKiller::killAllOptionsButSolutionFromCell( 7, $sut );
        $sut->setSolutionFromSingleCandidateIfPossible();

        $this->assertTrue( $sut->getSolutionValue()->equals( new OneToNineValue( 7 ) ) );
    }

    //-- Clue and solution values interaction -----------------------------//

    public function testSetSolutionValueThrowsExceptionIfClueIsSet()
    {
        $sut = $this->getSutWithClue( 5 );
        $this->expectException( \LogicException::class );
        CandidateKiller::killAllOptionsButSolutionFromCell( 5, $sut );
        $sut->setSolutionFromSingleCandidateIfPossible();
    }

    //-- Generic value wrapper --------------------------------------------//

    public function testHasNotValueAfterCreation()
    {
        $sut = $this->getSutWithoutClue();
        $this->assertFalse( $sut->hasValue() );
    }

    public function testHasValueAfterSettingAnClue()
    {
        $sut = $this->getSutWithClue( 5 );
        $this->assertTrue( $sut->hasValue() );
    }

    public function testHasValueAfterSettingASolutionValue()
    {
        $sut = $this->getSutWithoutClue();
        CandidateKiller::killAllOptionsButSolutionFromCell( 4, $sut );
        $sut->setSolutionFromSingleCandidateIfPossible();
        $this->assertTrue( $sut->hasValue() );
    }

    public function testGetValueTrowsExceptionIfNotSet()
    {
        $sut = $this->getSutWithoutClue();
        $this->expectException( \LogicException::class );
        $sut->getValue();
    }

    public function testGetValueReturnsClue()
    {
        $sut = $this->getSutWithClue( 5 );
        $this->assertTrue( $sut->getValue()->equals( new OneToNineValue( 5 ) ) );
    }

    public function testGetValueReturnsSolutionValue()
    {
        $sut = $this->getSutWithoutClue();
        CandidateKiller::killAllOptionsButSolutionFromCell( 4, $sut );
        $sut->setSolutionFromSingleCandidateIfPossible();
        $this->assertTrue( $sut->getValue()->equals( new OneToNineValue( 4 ) ) );
    }

    //-- Flagged as error -------------------------------------------------//

    public function testHasIncompatibleClueCallsCallback()
    {
        $this->sudokuMock->expects( $this->once() )
            ->method( 'checkIncompatibility' )
            ->with( $this->cellCoordinates )
            ->willReturn( false );

        $sut = $this->getSutWithoutClue();
        $sut->hasIncompatibleValue();
    }

    public function testHasNotIncompatibleValue()
    {
        $this->sudokuMock->method( 'checkIncompatibility' )->willReturn( false );

        $sut = $this->getSutWithoutClue();
        $this->assertFalse( $sut->hasIncompatibleValue() );
    }

    public function testHasIncompatibleValue()
    {
        $this->sudokuMock->method( 'checkIncompatibility' )->willReturn( true );

        $sut = $this->getSutWithoutClue();
        $this->assertTrue( $sut->hasIncompatibleValue() );
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

    //-- Private ----------------------------------------------------------//

    private function getSutWithoutClue() : Cell
    {
        return new Cell( $this->sudokuMock, $this->cellCoordinates );
    }

    private function getSutWithClue( int $clue ) : Cell
    {
        return new Cell( $this->sudokuMock, $this->cellCoordinates, new OneToNineValue( $clue ) );
    }
}
