<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Helpers;

use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;

class SudokuLoaderFileImplementation implements SudokuLoaderInterface
{
    public function gameExists( int $gameId )
    {
        return class_exists( 'XaviMontero\DrivewayOvershoot\Demo\Games\Game' . $gameId );
    }

    public function load( string $gameId, Sudoku $sudoku )
    {
        // TODO: Implement load() method.
    }
}
