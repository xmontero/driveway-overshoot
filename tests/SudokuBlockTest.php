<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\SudokuBlock;
use XaviMontero\DrivewayOvershoot\Cell;

class SudokuBlockTest extends \PHPUnit_Framework_TestCase
{
    private $sudokuMock;

    public function setup()
    {
        $this->sudokuMock = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\Sudoku' )
            ->setMethods( array( 'checkIncompatibility' ) )
            ->getMock();
    }

    public function testCreationIsOfProperClass()
    {
        $sut = $this->getSudokuBlockFromCellDefinition( [ 0, 0, 0, 0, 0, 1, 0, 0, 0 ], [ 0, 0, 2, 0, 0, 0, 0, 0, 0 ], 'row', new OneToNineValue( 4 ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $sut );
    }

    //-- Cell management --------------------------------------------------//

    public function testGetCell()
    {
        $emptyCells = $this->getEmptyCells( 'row', new OneToNineValue( 6 ) );

        $sut = $this->getSudokuBlockFromCells( $emptyCells );

        $columnId = 4;
        $expectedCell = $emptyCells[ $columnId ];
        $actualCell = $sut->getCell( new OneToNineValue( $columnId ) );

        $this->assertSame( $expectedCell, $actualCell );
    }

    //-- Is Empty ---------------------------------------------------------//

    /**
     * @dataProvider isEmptyProvider
     */
    public function testIsEmpty( array $clues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromCellDefinition( $clues, $solutionValues, $blockType, new OneToNineValue( $blockId ) );
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
    public function testHasIncompatibleValues( array $clues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromCellDefinition( $clues, $solutionValues, $blockType, new OneToNineValue( $blockId ) );
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
    public function testIsPerfect( array $clues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromCellDefinition( $clues, $solutionValues, $blockType, new OneToNineValue( $blockId ) );
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

        $sut = $this->getSudokuBlockFromCells( $emptyCellsYes );

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

        $sut = $this->getSudokuBlockFromCells( $emptyCellsYes );

        $this->expectException( \LogicException::class );
        $sut->cellIsIncompatible( $emptyCellsNo[ $x ] );
    }

    /**
     * @dataProvider cellIsIncompatibleProvider
     */
    public function testCellIsIncompatible( int $x, int $y, array $clues, array $solutionValues, string $blockType )
    {
        // TODO: ADD PROVIDER WITH POSITIVE AND NEGATIVE CASES TO MAKE THE METHOD FAIL.

        $blockId = $this->getBlockIdByCoordinatesAndBlockType( $x, $y, $blockType );

        $cells = $this->getCells( $clues, $solutionValues, $blockType, $blockId );
        $sut = $this->getSudokuBlockFromCells( $cells );

        $this->assertFalse( $sut->cellIsIncompatible( $cells[ $x ] ) );
    }

    public function cellIsIncompatibleProvider()
    {
        return
            [
                [ 5, 7, [ 0, 0, 0, 0, 0, 1, 0, 0, 0 ], [ 0, 0, 2, 0, 0, 0, 0, 0, 0 ], 'row' ],
            ];
    }

    //-- Private ----------------------------------------------------------//

    private function getSudokuBlockFromCellDefinition( array $clues, array $solutionValues, string $blockType, OneToNineValue $blockId ) : SudokuBlock
    {
        $cells = $this->getCells( $clues, $solutionValues, $blockType, $blockId );
        $sudokuBlock = $this->getSudokuBlockFromCells( $cells );

        return $sudokuBlock;
    }

    private function getCells( array $clues, array $solutionValues, string $blockType, OneToNineValue $blockId ) : array
    {
        $cells = $this->getEmptyCells( $blockType, $blockId );

        for( $i = 1; $i <= 9; $i++ )
        {
            $this->setCellValue( $clues[ $i - 1 ], $solutionValues[ $i - 1 ], $cells[ $i ] );
        }

        return $cells;
    }

    private function getEmptyCells( string $blockType, OneToNineValue $blockId ) : array
    {
        $cells = [ ];
        for( $i = 1; $i <= 9; $i++ )
        {
            $coordinates = $this->getCoordinates( new OneToNineValue( $i ), $blockType, $blockId );
            $cells[ $i ] = new Cell( $this->sudokuMock, $coordinates );
        }

        return $cells;
    }

    private function getCoordinates( OneToNineValue $positionInsideBlock, string $blockType, OneToNineValue $blockId ) : Coordinates
    {
        switch( $blockType )
        {
            case 'row':

                $columnId = $positionInsideBlock;
                $rowId = $blockId;
                break;

            case 'column':

                $columnId = $blockId;
                $rowId = $positionInsideBlock;
                break;

            case 'box':

                $xGross = ( ( $blockId->getValue() - 1 ) % 3 );
                $xFine = ( ( $positionInsideBlock->getValue() - 1 ) % 3 );
                $columnId = new OneToNineValue( $xGross * 3 + $xFine + 1 );

                $yGross = intdiv( ( $blockId->getValue() - 1 ), 3 );
                $yFine = intdiv( ( $positionInsideBlock->getValue() - 1 ), 3 );
                $rowId = new OneToNineValue( $yGross * 3 + $yFine + 1 );

                break;

            default:

                throw new \LogicException( "Undefined block type '$blockType'" );
                break;
        }

        return new Coordinates( $columnId, $rowId );
    }

    private function setCellValue( int $clue, int $solutionValue, Cell & $cell )
    {
        if( $clue > 0 )
        {
            $cell->setClue( new OneToNineValue( $clue ) );
        }
        else
        {
            if( $solutionValue > 0 )
            {
                $cell->setSolutionValue( new OneToNineValue( $solutionValue ) );
            }
        }
    }

    private function getSudokuBlockFromCells( array $cells ) : SudokuBlock
    {
        $sudokuBlock = new SudokuBlock( $cells[ 1 ], $cells[ 2 ], $cells[ 3 ], $cells[ 4 ], $cells[ 5 ], $cells[ 6 ], $cells[ 7 ], $cells[ 8 ], $cells[ 9 ] );

        return $sudokuBlock;
    }

    private function getBlockIdByCoordinatesAndBlockType( int $x, int $y, string $blockType )
    {
        switch( $blockType )
        {
            case 'row':

                $blockId = new OneToNineValue( $y );
                break;

            case 'column':

                $blockId = new OneToNineValue( $x );
                break;

            case 'box':

                throw new \Exception( 'Not implemented' );
                break;
        }

        return $blockId;
    }
}
