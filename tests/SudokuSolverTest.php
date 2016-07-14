<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\SudokuGrid;
use XaviMontero\DrivewayOvershoot\SudokuSolver;

class SudokuSolverTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $loader = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sudokuGrid = new SudokuGrid( $loader );
        $sut = new SudokuSolver( $sudokuGrid );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuSolver', $sut );
    }

    /**
     * @dataProvider isSolvedProvider
     */
    public function testIsSolved( string $gameId, bool $solved )
    {
        $loader = new Helpers\SudokuLoaderInMemoryImplementation( $gameId );
        $sudokuGrid = new SudokuGrid( $loader );
        $sut = new SudokuSolver( $sudokuGrid );

        $this->assertEquals( $solved, $sut->isSolved() );
    }

    public function isSolvedProvider()
    {
        return
            [
                [ 'easy1', false ],
                [ 'solved', true ],
                [ 'nearlySolved', false ],
                [ 'incompatibleSolved', false ],
            ];
    }

    public function testSolve()
    {
        $loader = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sudokuGrid = new SudokuGrid( $loader );
        $sut = new SudokuSolver( $sudokuGrid );

        $sut->solve();
        $this->assertTrue( $sut->isSolved() );
    }
}
