<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuSolver;

class SudokuSolverTest extends \PHPUnit_Framework_TestCase
{
    private $loader;
    private $sudoku;
    private $sut;

    protected function setUp()
    {
        $this->loader = new Helpers\SudokuLoaderInMemoryImplementation();

        $this->sudoku = new Sudoku();
        $this->sut = new SudokuSolver();
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuSolver', $this->getSut() );
    }

    public function testIsSolved()
    {
        $this->loader->load( 'easy1', $this->sudoku );

        $this->assertFalse( $this->getSut()->isSolved( $this->sudoku ) );
    }

    //-- Private ----------------------------------------------------------//

    private function getSut() : SudokuSolver
    {
        return $this->sut;
    }
}
