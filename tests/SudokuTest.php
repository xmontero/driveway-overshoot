<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Sudoku;
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
        $this->assertFalse( $this->sut->isEmpty() );
    }

    public function testProperValueAfterLoadingNonEmptyValues()
    {
        $this->loader->load( 'easy1', $this->sut );
        $this->assertFalse( $this->sut->isEmpty() );
        $this->assertTrue( $this->sut->getTile( new Coordinates( 2, 3 ) )->isEmpty() );
    }
}
