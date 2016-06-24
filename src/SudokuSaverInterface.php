<?php

namespace XaviMontero\DrivewayOvershoot;

interface SudokuSaverInterface
{
    public function save( string $gameId, Sudoku $sudoku );
}
