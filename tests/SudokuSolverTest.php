<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuSolver;

class SudokuSolverTest extends \PHPUnit_Framework_TestCase
{
    private $sudoku;
    private $sut;

    protected function setUp()
    {
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
        $loader = new Helpers\SudokuLoaderInMemoryImplementation( $gameId );
        $this->sudoku = new Sudoku( $loader );

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
