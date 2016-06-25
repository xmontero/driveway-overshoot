<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\SudokuFactory;

class SudokuFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $persister;
    private $sut;

    protected function setUp()
    {
        $this->persister = new Helpers\SudokuLoaderInMemoryImplementation();
        $this->sut = new SudokuFactory( $this->persister );
    }

    private function getSut() : SudokuFactory
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuFactory', $this->getSut() );
    }

    public function testCreateSudokuReturnsProperType()
    {
        $sudoku = $this->getSut()->createSudoku( 'easy1' );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Sudoku', $sudoku );
    }

    public function testCreationCallsTheReader()
    {
        $this->getSut()->createSudoku( 'easy1' );
        $this->assertEquals( 1, $this->persister->callCountLoad );
    }

    public function testCreationDoesPredefinedResult()
    {
        $sudoku = $this->getSut()->createSudoku( 'empty' );
        $this->assertTrue( $sudoku->isEmpty() );

        $sudoku = $this->getSut()->createSudoku( 'easy1' );
        $this->assertFalse( $sudoku->isEmpty() );
    }
}
