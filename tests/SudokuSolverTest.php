<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Grid;
use XaviMontero\DrivewayOvershoot\SudokuSolver;

class SudokuSolverTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $loader = new Helpers\GridLoaderInMemoryImplementation( 'easy1' );
        $grid = new Grid( $loader );
        $sut = new SudokuSolver( $grid );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuSolver', $sut );
    }

    /**
     * @dataProvider isSolvedProvider
     */
    public function testIsSolved( string $gameId, bool $solved )
    {
        $loader = new Helpers\GridLoaderInMemoryImplementation( $gameId );
        $grid = new Grid( $loader );
        $sut = new SudokuSolver( $grid );

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
        $loader = new Helpers\GridLoaderInMemoryImplementation( 'easy1' );
        $grid = new Grid( $loader );
        $sut = new SudokuSolver( $grid );

        $sut->solve();
        $this->assertTrue( $sut->isSolved() );
    }
}
