<?php

namespace XaviMontero\DrivewayOvershoot\Tests\Helpers;

use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;
use XaviMontero\DrivewayOvershoot\SudokuSaverInterface;

class SudokuPersisterInMemoryImplementation implements SudokuLoaderInterface, SudokuSaverInterface
{
    public $callCountLoad = 0;
    public $callCountSave = 0;

    public function load( string $gameId, Sudoku $sudoku )
    {
        $this->callCountLoad++;
    }

    public function save( string $gameId, Sudoku $sudoku )
    {
        //$this->callCountSave++;
    }
}
