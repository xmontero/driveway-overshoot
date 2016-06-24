<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Sudoku;

class SudokuTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
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
        $tile = $this->getSut()->getTile( 4, 4 );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Tile', $tile );
    }

    /*
    public function testAfterSettingValuesIsNotEmpty()
    {
        $sut = new Sudoku();
        $sut->getTile( 4, 4 )->setValue( 9 );
        $this->assertFalse( $sut->isEmpty() );
    }
    */
}
