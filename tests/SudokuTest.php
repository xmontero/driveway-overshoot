<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuState;
use XaviMontero\DrivewayOvershoot\Value;

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
        $tile = $this->getSut()->getTile( new Coordinates( 4, 4 ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Tile', $tile );
    }

    public function testIsNotEmptyAfterSettingValues()
    {
        $this->sut->getTile( new Coordinates( 4, 4 ) )->setInitialValue( new Value( 9 ) );
        $this->assertFalse( $this->getSut()->isEmpty() );
    }

    public function testProperValueAfterLoadingNonEmptyValues()
    {
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->getSut()->isEmpty() );
        $this->assertTrue( $this->getSut()->getTile( new Coordinates( 2, 3 ) )->isEmpty() );
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

    public function testCheckIncompatibility()
    {
        $sut = $this->getSut();

        $this->assertFalse( $sut->checkIncompatibility( new Coordinates( 1, 1 ) ) );
    }
}
