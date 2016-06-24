<?php

namespace XaviMontero\DrivewayOvershoot;

class SudokuFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $persister;
    private $sut;

    protected function setUp()
    {
        $this->persister = new Tests\Helpers\SudokuPersisterInMemoryImplementation();
        $this->sut = new SudokuFactory( $this->persister, $this->persister );
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
        $sudoku = $this->getSut()->createSudoku( 'game1' );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Sudoku', $sudoku );
    }

    public function testCreationCallsTheReader()
    {
        $this->getSut()->createSudoku( 'game1' );
        $this->assertEquals( 1, $this->persister->callCountLoad );
    }
}
