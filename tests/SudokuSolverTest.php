<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
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

    /**
     * @dataProvider isSolvedProvider
     */
    public function testIsSolved( string $gameId, bool $solved )
    {
        $this->loader->load( $gameId, $this->sudoku );

        $this->assertEquals( $solved, $this->getSut()->isSolved( $this->sudoku ) );
    }

    public function isSolvedProvider()
    {
        return
            [
                [ 'easy1', false ],
                [ 'solved', true ],
                [ 'nearlySolved', false ],
            ];
    }

    //-- Private ----------------------------------------------------------//

    private function getSut() : SudokuSolver
    {
        return $this->sut;
    }
}
