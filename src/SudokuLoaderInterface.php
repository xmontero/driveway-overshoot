<?php

namespace XaviMontero\DrivewayOvershoot;

interface SudokuLoaderInterface
{
    public function load( string $gameId, Sudoku $sudoku );
}
