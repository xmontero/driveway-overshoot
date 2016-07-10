<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Interface that provides a way for the SudokuFactory to load games into the newly created Sudoku.
 */
interface SudokuLoaderInterface
{
    public function load( string $gameId, Sudoku $sudoku );
}
