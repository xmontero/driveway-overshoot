<?php

namespace XaviMontero\DrivewayOvershoot\Tests\Helpers;

use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;
use XaviMontero\DrivewayOvershoot\SudokuSaverInterface;

class SudokuPersisterInMemoryImplementation implements SudokuLoaderInterface, SudokuSaverInterface
{
    public function load( Sudoku $sudoku )
    {
        // TODO: Implement load() method.
    }

    public function save( Sudoku $sudoku )
    {
        // TODO: Implement save() method.
    }
}
