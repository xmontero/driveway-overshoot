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

    public function testGetTileReturnsProperType()
    {
        $tile = $this->getSut()->getTile( new Coordinates( new OneToNineValue( 4 ), new OneToNineValue( 4 ) ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Tile', $tile );
    }

    public function testIsNotEmptyAfterSettingValues()
    {
        $this->sut->getTile( new Coordinates( new OneToNineValue( 4 ), new OneToNineValue( 4 ) ) )->setInitialValue( new OneToNineValue( 9 ) );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }

    public function testProperValueAfterLoadingNonEmptyValues()
    {
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->getSut()->isEmpty() );
        $this->assertTrue( $this->getSut()->getTile( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 3 ) ) )->isEmpty() );
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
                [ 'incompatibleInitialValuesRow', 8, 8, false ],
                [ 'incompatibleInitialValuesRow', 4, 3, false ],
                [ 'incompatibleInitialValuesRow', 1, 4, true ],
/*              [ 'incompatibleInitialValuesRow', 9, 4, true ],
                [ 'incompatibleInitialValuesColumn', 1, 5, false ],
                [ 'incompatibleInitialValuesColumn', 9, 9, false ],
                [ 'incompatibleInitialValuesColumn', 6, 3, true ],
                [ 'incompatibleInitialValuesColumn', 6, 8, true ],
                [ 'incompatibleInitialValuesSquare', 3, 9, false ],
                [ 'incompatibleInitialValuesSquare', 2, 5, false ],
                [ 'incompatibleInitialValuesSquare', 4, 1, true ],
                [ 'incompatibleInitialValuesSquare', 6, 2, true ],
                [ 'incompatibleInitialValuesHard', 6, 1, false ],
                [ 'incompatibleInitialValuesHard', 3, 9, false ],
                [ 'incompatibleInitialValuesHard', 5, 2, false ],
                [ 'incompatibleInitialValuesHard', 6, 7, false ],
                [ 'incompatibleInitialValuesHard', 1, 5, true ],
                [ 'incompatibleInitialValuesHard', 8, 8, true ],
                [ 'incompatibleInitialValuesHard', 3, 3, true ],
                [ 'incompatibleInitialValuesHard', 9, 7, true ],
                [ 'incompatibleInitialValuesHard', 9, 2, true ],
                [ 'incompatibleInitialValuesHard', 3, 6, true ],
*/            ];
    }

    public function testHasNotIncompatibleInitialValues()
    {
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->getSut()->hasIncompatibleInitialValues() );
    }

    /**
     * @dataProvider hasIncompatibleInitialValuesProvider
     */
    public function testHasIncompatibleInitialValues( $sudokuName )
    {
        $this->loader->load( $sudokuName, $this->sut );
        $this->assertTrue( $this->getSut()->hasIncompatibleInitialValues() );
    }

    public function hasIncompatibleInitialValuesProvider()
    {
        return
        [
            [ 'incompatibleInitialValuesRow' ],
            [ 'incompatibleInitialValuesColumn' ],
            [ 'incompatibleInitialValuesSquare' ],
            [ 'incompatibleInitialValuesHard' ],
        ];
    }

    //-- Coordinates and squares ------------------------------------------//

    public function testGetRowBlockByTile()
    {
        $this->loader->load( 'easy1', $this->sut );

        $sut = $this->getSut();

        $tile = $sut->getTile( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 8 ) ) );
        $row = $sut->getRowBlockByTile( $tile );

        $this->assertTrue( $row->hasTile( $tile ) );
    }
}
