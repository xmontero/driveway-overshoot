<?php

namespace XaviMontero\DrivewayOvershoot;

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
