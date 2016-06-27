<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\SudokuBlock;
use XaviMontero\DrivewayOvershoot\Tile;
use XaviMontero\DrivewayOvershoot\Value;

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
        $sut = $this->getSudokuBlockFromTileDefinition( [ 0, 0, 0, 0, 0, 1, 0, 0, 0 ], [ 0, 0, 2, 0, 0, 0, 0, 0, 0 ], 'row', 4 );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $sut );
    }

    //-- Is Empty ---------------------------------------------------------//

    /**
     * @dataProvider isEmptyProvider
     */
    public function testIsEmpty( array $initialValues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromTileDefinition( $initialValues, $solutionValues, $blockType, $blockId );
        $this->assertEquals( $expected, $sut->isEmpty() );
    }

    public function isEmptyProvider()
    {
        return
        [
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, true ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 7, true ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'square', 2, true ],

            [ [ 0, 3, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 1, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 7, 0 ], 'square', 9, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 8, 0 ], [ 0, 0, 0, 0, 5, 0, 0, 0, 0 ], 'row', 6, false ],

            [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'square', 4, false ],
            [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, false ],
            [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, false ],
        ];
    }

    //-- Has Incompatible Values ------------------------------------------//

    /**
     * @dataProvider hasIncompatibleValuesProvider
     */
    public function testHasIncompatibleValues( array $initialValues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromTileDefinition( $initialValues, $solutionValues, $blockType, $blockId );
        $this->assertEquals( $expected, $sut->hasIncompatibleValues() );
    }

    public function hasIncompatibleValuesProvider()
    {
        return
            [
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, false ],

                [ [ 0, 3, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 1, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 7, 0 ], 'square', 9, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 8, 0 ], [ 0, 0, 0, 0, 5, 0, 0, 0, 0 ], 'row', 6, false ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'square', 4, false ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, false ],
                [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, false ],

                [ [ 0, 0, 2, 0, 0, 2, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 1, 1, 0, 0, 0, 0, 0, 0 ], 'column', 7, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 7 ], [ 0, 0, 0, 7, 0, 0, 0, 0, 0 ], 'square', 2, true ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 9 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'square', 4, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 1, 6, 5, 4, 3, 2, 1 ], 'row', 3, true ],
                [ [ 1, 3, 5, 0, 2, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, true ],
            ];
    }

    //-- Is Perfect -------------------------------------------------------//

    /**
     * @dataProvider isPerfectProvider
     */
    public function testIsPerfect( array $initialValues, array $solutionValues, string $blockType, int $blockId, bool $expected )
    {
        $sut = $this->getSudokuBlockFromTileDefinition( $initialValues, $solutionValues, $blockType, $blockId );
        $this->assertEquals( $expected, $sut->isPerfect() );
    }

    public function isPerfectProvider()
    {
        return
            [
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'row', 5, false ],
                [ [ 1, 1, 1, 1, 1, 1, 1, 1, 1 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'column', 7, false ],
                [ [ 0, 2, 0, 0, 0, 8, 0, 0, 0 ], [ 7, 0, 4, 0, 0, 0, 0, 0, 1 ], 'square', 2, false ],

                [ [ 1, 3, 5, 7, 9, 2, 4, 6, 8 ], [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], 'square', 4, true ],
                [ [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], [ 9, 8, 7, 6, 5, 4, 3, 2, 1 ], 'row', 3, true ],
                [ [ 1, 3, 5, 0, 7, 0, 0, 0, 0 ], [ 0, 0, 0, 6, 0, 4, 8, 2, 9 ], 'column', 8, true ],
            ];
    }

    //-- Private ----------------------------------------------------------//

    private function getSudokuBlockFromTileDefinition( array $initialValues, array $solutionValues, string $blockType, int $blockId )
    {
        $tiles = $this->getTiles( $initialValues, $solutionValues, $blockType, $blockId );
        $sudokuBlock = new SudokuBlock( $tiles[ 1 ], $tiles[ 2 ], $tiles[ 3 ], $tiles[ 4 ], $tiles[ 5 ], $tiles[ 6 ], $tiles[ 7 ], $tiles[ 8 ], $tiles[ 9 ] );

        return $sudokuBlock;
    }

    private function getTiles( array $initialValues, array $solutionValues, string $blockType, int $blockId )
    {
        $tiles = $this->getEmptyTiles( $blockType, $blockId );

        for( $i = 1; $i <= 9; $i++ )
        {
            $this->setTileValue( $initialValues[ $i - 1 ], $solutionValues[ $i - 1 ], $tiles[ $i ] );
        }

        return $tiles;
    }

    private function getEmptyTiles( string $blockType, int $blockId ) : array
    {
        $tiles = [];
        for( $i = 1; $i <= 9; $i++ )
        {
            $coordinates = $this->getCoordinates( $i, $blockType, $blockId );
            $tiles[ $i ] = new Tile( $this->sudokuMock, $coordinates );
        }

        return $tiles;
    }

    private function getCoordinates( int $positionInsideBlock, string $blockType, int $blockId )
    {
        switch( $blockType )
        {
            case 'row':

                $x = $positionInsideBlock;
                $y = $blockId;
                break;

            case 'column':

                $x = $blockId;
                $y = $positionInsideBlock;
                break;

            case 'square':

                $xGross = ( ( $blockId - 1 ) % 3 );
                $xFine = ( ( $positionInsideBlock - 1 ) % 3 );
                $x = $xGross * 3 + $xFine + 1;

                $yGross = intdiv( ( $blockId - 1 ), 3 );
                $yFine = intdiv( ( $positionInsideBlock - 1 ), 3 );
                $y = $yGross * 3 + $yFine + 1;

                break;

            default:

                throw new \LogicException( "Undefined block type '$blockType'" );
                break;
        }

        return new Coordinates( $x, $y );
    }

    private function setTileValue( int $initialValue, int $solutionValue, Tile & $tile )
    {
        if( $initialValue > 0 )
        {
            $tile->setInitialValue( new Value( $initialValue ) );
        }
        else
        {
            if( $solutionValue > 0 )
            {
                $tile->setSolutionValue( new Value( $solutionValue ) );
            }
        }
    }
}
