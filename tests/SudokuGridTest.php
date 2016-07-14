<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\SudokuGrid;

class SudokuGridTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuGrid', $this->getSut( 'empty' ) );
    }

    public function testGetCellReturnsProperType()
    {
        $cell = $this->getSut( 'empty' )->getCell( new Coordinates( new OneToNineValue( 4 ), new OneToNineValue( 4 ) ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Cell', $cell );
    }

    //-- Check incompatibility --------------------------------------------//

    /**
     * @dataProvider checkIncompatibilityProvider
     */
    public function testCheckIncompatibility( $sudokuName, $x, $y, $expected )
    {
        $sut = $this->getSut( $sudokuName );
        $this->assertEquals( $expected, $sut->checkIncompatibility( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) ) );
    }

    public function checkIncompatibilityProvider()
    {
        return
            [
                [ 'easy1', 3, 4, false ],
                [ 'easy1', 9, 9, false ],
                [ 'incompatibleCluesInRow', 8, 8, false ],
                [ 'incompatibleCluesInRow', 4, 3, false ],
                [ 'incompatibleCluesInRow', 1, 4, true ],
/*              [ 'incompatibleCluesInRow', 9, 4, true ],
                [ 'incompatibleCluesInColumn', 1, 5, false ],
                [ 'incompatibleCluesInColumn', 9, 9, false ],
                [ 'incompatibleCluesInColumn', 6, 3, true ],
                [ 'incompatibleCluesInColumn', 6, 8, true ],
                [ 'incompatibleCluesInBox', 3, 9, false ],
                [ 'incompatibleCluesInBox', 2, 5, false ],
                [ 'incompatibleCluesInBox', 4, 1, true ],
                [ 'incompatibleCluesInBox', 6, 2, true ],
                [ 'incompatibleCluesHard', 6, 1, false ],
                [ 'incompatibleCluesHard', 3, 9, false ],
                [ 'incompatibleCluesHard', 5, 2, false ],
                [ 'incompatibleCluesHard', 6, 7, false ],
                [ 'incompatibleCluesHard', 1, 5, true ],
                [ 'incompatibleCluesHard', 8, 8, true ],
                [ 'incompatibleCluesHard', 3, 3, true ],
                [ 'incompatibleCluesHard', 9, 7, true ],
                [ 'incompatibleCluesHard', 9, 2, true ],
                [ 'incompatibleCluesHard', 3, 6, true ],
*/            ];
    }

    public function testHasNotIncompatibleClues()
    {
        $sut = $this->getSut( 'easy1' );
        $this->assertFalse( $sut->hasIncompatibleClues() );
    }

    /**
     * @dataProvider hasIncompatibleCluesProvider
     */
    public function testHasIncompatibleClues( $sudokuName )
    {
        $sut = $this->getSut( $sudokuName );
        $this->assertTrue( $sut->hasIncompatibleClues() );
    }

    public function hasIncompatibleCluesProvider()
    {
        return
        [
            [ 'incompatibleCluesInRow' ],
            [ 'incompatibleCluesInColumn' ],
            [ 'incompatibleCluesInBox' ],
            [ 'incompatibleCluesHard' ],
        ];
    }

    //-- Coordinates and boxes ------------------------------------------//

    public function testGetRowBlockByCell()
    {
        $sut = $this->getSut( 'easy1' );

        $cell = $sut->getCell( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 8 ) ) );
        $row = $sut->getRowBlockByCell( $cell );

        $this->assertTrue( $row->hasCell( $cell ) );
    }

    public function testGetRowBlockIsOfProperClass()
    {
        $sut = $this->getSut( 'easy1' );

        $rowId = new OneToNineValue( 4 );
        $row = $sut->getRowBlock( $rowId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $row );
    }

    public function testGetRowBlock()
    {
        $sut = $this->getSut( 'easy1' );

        $rowId = new OneToNineValue( 4 );
        $row = $sut->getRowBlock( $rowId );

        for( $x = 1; $x <= 9; $x++ )
        {
            $columnId = new OneToNineValue( $x );

            $expectedCell = $sut->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $row->getCell( $columnId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }

    public function testGetColumnBlockIsOfProperClass()
    {
        $sut = $this->getSut( 'easy1' );

        $columnId = new OneToNineValue( 4 );
        $column = $sut->getColumnBlock( $columnId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $column );
    }

    public function testGetColumnBlock()
    {
        $sut = $this->getSut( 'easy1' );

        $columnId = new OneToNineValue( 7 );
        $column = $sut->getColumnBlock( $columnId );

        for( $y = 1; $y <= 9; $y++ )
        {
            $rowId = new OneToNineValue( $y );

            $expectedCell = $sut->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $column->getCell( $rowId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }

    public function testGetBoxBlockIsOfProperClass()
    {
        $sut = $this->getSut( 'easy1' );

        $boxId = new OneToNineValue( 4 );
        $box = $sut->getBoxBlock( $boxId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $box );
    }

    public function testGetBoxBlock()
    {
        $sut = $this->getSut( 'easy1' );

        $boxId = new OneToNineValue( 7 );
        $box = $sut->getBoxBlock( $boxId );

        $allExpectedCoordinates = [
            1 => [ 'column' => 1, 'row' => 7 ],
            2 => [ 'column' => 2, 'row' => 7 ],
            3 => [ 'column' => 3, 'row' => 7 ],
            4 => [ 'column' => 1, 'row' => 8 ],
            5 => [ 'column' => 2, 'row' => 8 ],
            6 => [ 'column' => 3, 'row' => 8 ],
            7 => [ 'column' => 1, 'row' => 9 ],
            8 => [ 'column' => 2, 'row' => 9 ],
            9 => [ 'column' => 3, 'row' => 9 ],
        ];

        for( $position = 1; $position <= 9; $position++ )
        {
            $positionId = new OneToNineValue( $position );

            $expectedCoordinates = $allExpectedCoordinates[ $position ];
            $columnId = new OneToNineValue( $expectedCoordinates[ 'column' ] );
            $rowId = new OneToNineValue( $expectedCoordinates[ 'row' ] );

            $expectedCell = $sut->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $box->getCell( $positionId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }

    //-- Private ----------------------------------------------------------//

    private function getSut( string $gameId ) : SudokuGrid
    {
        $loader = new Helpers\SudokuLoaderInMemoryImplementation( $gameId );
        $sut = new SudokuGrid( $loader );

        return $sut;
    }
}
