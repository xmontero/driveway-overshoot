<?php

namespace XaviMontero\DrivewayOvershoot;

class SudokuFactory
{
    private $reader;
    private $gameId;

    public function __construct( SudokuLoaderInterface $reader, SudokuSaverInterface $writer )
    {
        $this->reader = $reader;
    }

    public function createSudoku( string $gameId ) : Sudoku
    {
        $this->gameId = $gameId;

        $sudoku = new Sudoku();
        $this->reader->load( $gameId, $sudoku );
        return $sudoku;
    }
}
