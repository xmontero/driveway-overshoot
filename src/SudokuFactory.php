<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Factory service to create and load Sudoku objects.
 *
 * The loading takes place from an injected service implementing SudokuLoaderInterface which is able to provide data for the clues of the problem to be solved.
 */
class SudokuFactory
{
    private $reader;
    private $gameId;

    public function __construct( SudokuLoaderInterface $loader )
    {
        $this->loader = $loader;
    }

    public function createSudoku( string $gameId ) : Sudoku
    {
        $this->gameId = $gameId;

        $sudoku = new Sudoku();
        $this->loader->load( $gameId, $sudoku );
        return $sudoku;
    }
}
