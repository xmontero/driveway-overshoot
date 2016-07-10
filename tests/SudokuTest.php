<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuState;

class SudokuTest extends \PHPUnit_Framework_TestCase
{
    private $loader;
    private $saver;
    private $sut;

    protected function setUp()
    {
        $this->loader = new Helpers\SudokuLoaderInMemoryImplementation();
        $this->saver = $this->loader;

        $this->sut = new Sudoku();
    }

    private function getSut() : Sudoku
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Sudoku', $this->getSut() );
    }

    public function testAfterCreationIsEmpty()
    {
        $this->assertTrue( $this->getSut()->isEmpty() );
    }

    public function testGetCellReturnsProperType()
    {
        $cell = $this->getSut()->getCell( new Coordinates( new OneToNineValue( 4 ), new OneToNineValue( 4 ) ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Cell', $cell );
    }

    public function testIsNotEmptyAfterSettingValues()
    {
        $this->sut->getCell( new Coordinates( new OneToNineValue( 4 ), new OneToNineValue( 4 ) ) )->setClue( new OneToNineValue( 9 ) );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }

    public function testProperValueAfterLoadingNonEmptyValues()
    {
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->getSut()->isEmpty() );
        $this->assertTrue( $this->getSut()->getCell( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 3 ) ) )->isEmpty() );
    }

    //-- Editable ---------------------------------------------------------//

    public function testIsEditableAfterCreation()
    {
        $sut = $this->getSut();

        $this->assertTrue( $sut->isEditable() );
        $this->assertEquals( SudokuState::Editable(), $sut->getState() );
    }

    public function testChangeEditableMultipleTimes()
    {
        $sut = $this->getSut();

        $sut->setEditable( false );
        $this->assertFalse( $sut->isEditable() );
        $this->assertNotEquals( SudokuState::Editable(), $sut->getState() );

        $sut->setEditable( true );
        $this->assertTrue( $sut->isEditable() );
        $this->assertEquals( SudokuState::Editable(), $sut->getState() );
    }

    public function testChangeEditableOnceTriggersEventOnce()
    {
        $sudokuObserver = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\SudokuObserverInterface' )
            ->setMethods( array( 'onEditableChanged' ) )
            ->getMock();

        $sudokuObserver->expects( $this->once() )
            ->method( 'onEditableChanged' )
            ->with( $this->equalTo( false ) );

        $sut = $this->getSut();
        $sut->addObserver( $sudokuObserver );

        $sut->setEditable( false );
    }

    public function testChangeEditableTwiceWithSameValueTriggersEventOnce()
    {
        $sudokuObserver = $this->getMockBuilder( 'XaviMontero\DrivewayOvershoot\SudokuObserverInterface' )
            ->setMethods( array( 'onEditableChanged' ) )
            ->getMock();

        $sudokuObserver->expects( $this->once() )
            ->method( 'onEditableChanged' )
            ->with( $this->equalTo( false ) );

        $sut = $this->getSut();
        $sut->addObserver( $sudokuObserver );

        $sut->setEditable( false );
        $sut->setEditable( false );
    }

    //-- Check incompatibility --------------------------------------------//

    /**
     * @dataProvider checkIncompatibilityProvider
     */
    public function testCheckIncompatibility( $sudokuName, $x, $y, $expected )
    {
        $this->loader->load( $sudokuName, $this->sut );
        $this->assertEquals( $expected, $this->getSut()->checkIncompatibility( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) ) );
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
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->getSut()->hasIncompatibleClues() );
    }

    /**
     * @dataProvider hasIncompatibleCluesProvider
     */
    public function testHasIncompatibleClues( $sudokuName )
    {
        $this->loader->load( $sudokuName, $this->sut );
        $this->assertTrue( $this->getSut()->hasIncompatibleClues() );
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
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $cell = $sut->getCell( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 8 ) ) );
        $row = $sut->getRowBlockByCell( $cell );

        $this->assertTrue( $row->hasCell( $cell ) );
    }

    public function testGetRowBlockIsOfProperClass()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $rowId = new OneToNineValue( 4 );
        $row = $sut->getRowBlock( $rowId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $row );
    }

    public function testGetRowBlock()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $rowId = new OneToNineValue( 4 );
        $row = $sut->getRowBlock( $rowId );

        for( $x = 1; $x <= 9; $x++ )
        {
            $columnId = new OneToNineValue( $x );

            $expectedCell = $this->getSut()->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $row->getCell( $columnId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }

    public function testGetColumnBlockIsOfProperClass()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $columnId = new OneToNineValue( 4 );
        $column = $sut->getColumnBlock( $columnId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $column );
    }

    public function testGetColumnBlock()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $columnId = new OneToNineValue( 7 );
        $column = $sut->getColumnBlock( $columnId );

        for( $y = 1; $y <= 9; $y++ )
        {
            $rowId = new OneToNineValue( $y );

            $expectedCell = $this->getSut()->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $column->getCell( $rowId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }

    public function testGetBoxBlockIsOfProperClass()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

        $boxId = new OneToNineValue( 4 );
        $box = $sut->getBoxBlock( $boxId );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuBlock', $box );
    }

    public function testGetBoxBlock()
    {
        $sut = $this->getSut();
        $this->loader->load( 'easy1', $sut );

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

            $expectedCell = $this->getSut()->getCell( new Coordinates( $columnId, $rowId ) );
            $actualCell = $box->getCell( $positionId );

            $this->assertSame( $expectedCell, $actualCell );
        }
    }
}
