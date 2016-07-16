<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Unit;
use XaviMontero\DrivewayOvershoot\Cell;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    private $sudokuMock;

    public function setup()
    {
        $this->sudokuMock = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\SudokuGrid' )
            ->disableOriginalConstructor()
            ->setMethods( array( 'checkIncompatibility' ) )
            ->getMock();
    }

    public function testCreationIsOfProperClass()
    {
        $sut = $this->getUnitFromCellDefinition( [ 0, 0, 0, 0, 0, 1, 0, 0, 0 ], [ 0, 0, 2, 0, 0, 0, 0, 0, 0 ], 'row', new OneToNineValue( 4 ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Unit', $sut );
    }

    //-- Cell management --------------------------------------------------//

    public function testGetCell()
    {
        $emptyCells = $this->getEmptyCells( 'row', new OneToNineValue( 6 ) );

        $sut = $this->getUnitFromCells( $emptyCells );

        $columnId = 4;
        $expectedCell = $emptyCells[ $columnId ];
        $actualCell = $sut->getCell( new OneToNineValue( $columnId ) );

        $this->assertSame( $expectedCell, $actualCell );
    }

    //-- Is Empty ---------------------------------------------------------//

    /**
     * @dataProvider isEmptyProvider
     */
    public function testIsEmpty( array $clues, array $solutionValues, string $unitType, int $unitId, bool $expected )
    {
        $sut = $this->getUnitFromCellDefinition( $clues, $solutionValues, $unitType, new OneToNineValue( $unitId ) );
        $this->assertEquals( $expected, $sut->isEmpty() );
    }

    public function isEmptyProvider()
    {
        return
        [
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, true ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 7, true ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'box', 2, true ],

            [ [ 0, 3, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 1, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 7, 0 ], 'box', 9, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 8, 0 ], [ 0, 0, 0, 0, 5, 0, 0, 0, 0 ], 'row', 6, false ],

            [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'box', 4, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, false ],
            [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, false ],
        ];
    }

    //-- Has Incompatible Values ------------------------------------------//

    /**
     * @dataProvider hasIncompatibleValuesProvider
     */
    public function testHasIncompatibleValues( array $clues, array $solutionValues, string $unitType, int $unitId, bool $expected )
    {
        $sut = $this->getUnitFromCellDefinition( $clues, $solutionValues, $unitType, new OneToNineValue( $unitId ) );
        $this->assertEquals( $expected, $sut->hasIncompatibleValues() );
    }

    public function hasIncompatibleValuesProvider()
    {
        return
            [
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, false ],

                [ [ 0, 3, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 1, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 7, 0 ], 'box', 9, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 8, 0 ], [ 0, 0, 0, 0, 5, 0, 0, 0, 0 ], 'row', 6, false ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'box', 4, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, false ],
                [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, false ],

                [ [ 0, 0, 2, 0, 0, 2, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 1, 1, 0, 0, 0, 0, 0, 0 ], 'column', 7, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 7 ], [ 0, 0, 0, 7, 0, 0, 0, 0, 0 ], 'box', 2, true ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 9 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'box', 4, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 1, 6, 5, 4, 3, 2, 1 ], 'row', 3, true ],
                [ [ 1, 3, 5, 0, 2, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, true ],
            ];
    }

    //-- Is Perfect -------------------------------------------------------//

    /**
     * @dataProvider isPerfectProvider
     */
    public function testIsPerfect( array $clues, array $solutionValues, string $unitType, int $unitId, bool $expected )
    {
        $sut = $this->getUnitFromCellDefinition( $clues, $solutionValues, $unitType, new OneToNineValue( $unitId ) );
        $this->assertEquals( $expected, $sut->isPerfect() );
    }

    public function isPerfectProvider()
    {
        return
            [
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, false ],
                [ [ 1, 1, 1, 1, 1, 1, 1, 1, 1 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 7, false ],
                [ [ 0, 2, 0, 0, 0, 8, 0, 0, 0 ], [ 7, 0, 4, 0, 0, 0, 0, 0, 1 ], 'box', 2, false ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'box', 4, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, true ],
                [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, true ],
            ];
    }

    //-- Cell management --------------------------------------------------//

    public function testHasCell()
    {
        $x = 5;
        $y = 7;

        $emptyCellsYes = $this->getEmptyCells( 'row', new OneToNineValue( $y ) );
        $emptyCellsNo = $this->getEmptyCells( 'row', new OneToNineValue( $y + 1 ) );

        $sut = $this->getUnitFromCells( $emptyCellsYes );

        $this->assertTrue( $sut->hasCell( $emptyCellsYes[ $x ] ) );
        $this->assertFalse( $sut->hasCell( $emptyCellsNo[ $x ] ) );
    }

    //-- Specific cell incompatibility ------------------------------------//

    public function testCellIsIncompatibleThrowsExceptionIfCellIsNotFound()
    {
        $x = 5;
        $y = 7;

        $emptyCellsYes = $this->getEmptyCells( 'row', new OneToNineValue( $y ) );
        $emptyCellsNo = $this->getEmptyCells( 'row', new OneToNineValue( $y + 1 ) );

        $sut = $this->getUnitFromCells( $emptyCellsYes );

        $this->expectException( \LogicException::class );
        $sut->cellIsIncompatible( $emptyCellsNo[ $x ] );
    }

    /**
     * @dataProvider cellIsIncompatibleProvider
     */
    public function testCellIsIncompatible( int $x, int $y, array $clues, array $solutionValues, string $unitType )
    {
        // TODO: ADD PROVIDER WITH POSITIVE AND NEGATIVE CASES TO MAKE THE METHOD FAIL.

        $unitId = $this->getUnitIdByCoordinatesAndUnitType( $x, $y, $unitType );

        $cells = $this->getCells( $clues, $solutionValues, $unitType, $unitId );
        $sut = $this->getUnitFromCells( $cells );

        $this->assertFalse( $sut->cellIsIncompatible( $cells[ $x ] ) );
    }

    public function cellIsIncompatibleProvider()
    {
        return
            [
                [ 5, 7, [ 0, 0, 0, 0, 0, 1, 0, 0, 0 ], [ 0, 0, 2, 0, 0, 0, 0, 0, 0 ], 'row' ],
            ];
    }

    public function testGetCellsAsArray()
    {
        $cells = $this->getCells( [ 1, 2, 0, 4, 5, 9, 8, 0, 6 ], [ 0, 0, 3, 0, 0, 0, 0, 7, 0 ], 'box', new OneToNineValue( 4 ) );
        $sut = $this->getUnitFromCells( $cells );

        foreach( $sut->getCellsAsArray() as $cellId => $cell )
        {
            $this->assertSame( $cells[ $cellId ], $cell );
        }
    }

    //-- Private ----------------------------------------------------------//

    private function getUnitFromCellDefinition( array $clues, array $solutionValues, string $unitType, OneToNineValue $unitId ) : Unit
    {
        $cells = $this->getCells( $clues, $solutionValues, $unitType, $unitId );
        $unit = $this->getUnitFromCells( $cells );

        return $unit;
    }

    private function getCells( array $clues, array $solutionValues, string $unitType, OneToNineValue $unitId ) : array
    {
        $cells = [ ];

        for( $i = 1; $i <= 9; $i++ )
        {
            $coordinates = $this->getCoordinates( new OneToNineValue( $i ), $unitType, $unitId );

            $clueValue = $clues[ $i - 1 ];
            $solutionValue = $solutionValues[ $i - 1 ];

            $clue = ( $clueValue == 0 ) ? null : new OneToNineValue( $clueValue );
            $cell = new Cell( $this->sudokuMock, $coordinates, $clue );

            if( $solutionValue > 0 )
            {
                $this->killAllOptionsButSolution( $solutionValue, $cell );
                $cell->setSolutionFromSingleCandidateIfPossible();
            }

            $cells[ $i ] = $cell;
        }

        return $cells;
    }

    private function getEmptyCells( string $unitType, OneToNineValue $unitId ) : array
    {
        $clues = [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
        $solutionValues = [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

        return $this->getCells( $clues, $solutionValues, $unitType, $unitId );
    }

    private function getCoordinates( OneToNineValue $positionInsideUnit, string $unitType, OneToNineValue $unitId ) : Coordinates
    {
        switch( $unitType )
        {
            case 'row':

                $columnId = $positionInsideUnit;
                $rowId = $unitId;
                break;

            case 'column':

                $columnId = $unitId;
                $rowId = $positionInsideUnit;
                break;

            case 'box':

                $xGross = ( ( $unitId->getValue() - 1 ) % 3 );
                $xFine = ( ( $positionInsideUnit->getValue() - 1 ) % 3 );
                $columnId = new OneToNineValue( $xGross * 3 + $xFine + 1 );

                $yGross = intdiv( ( $unitId->getValue() - 1 ), 3 );
                $yFine = intdiv( ( $positionInsideUnit->getValue() - 1 ), 3 );
                $rowId = new OneToNineValue( $yGross * 3 + $yFine + 1 );

                break;

            default:

                throw new \LogicException( "Undefined unit type '$unitType'" );
                break;
        }

        return new Coordinates( $columnId, $rowId );
    }

    private function getUnitFromCells( array $cells ) : Unit
    {
        $unit = new Unit( $cells[ 1 ], $cells[ 2 ], $cells[ 3 ], $cells[ 4 ], $cells[ 5 ], $cells[ 6 ], $cells[ 7 ], $cells[ 8 ], $cells[ 9 ] );

        return $unit;
    }

    private function getUnitIdByCoordinatesAndUnitType( int $x, int $y, string $unitType )
    {
        switch( $unitType )
        {
            case 'row':

                $unitId = new OneToNineValue( $y );
                break;

            case 'column':

                $unitId = new OneToNineValue( $x );
                break;

            case 'box':

                throw new \Exception( 'Not implemented' );
                break;
        }

        return $unitId;
    }

    private function killAllOptionsButSolution( int $solutionValue, Cell $cell )
    {
        // TODO: Remove duplication from CellTest.
        $candidates = $cell->getCandidates();

        for( $v = 1; $v <= 9; $v++ )
        {
            if( $v != $solutionValue )
            {
                $candidates->killOption( new OneToNineValue( $v ) );
            }
        }
    }
}
