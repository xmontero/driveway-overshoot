<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Helpers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;

class SudokuLoaderFileImplementation implements SudokuLoaderInterface
{
    public function gameExists( int $gameId )
    {
        return class_exists( $this->getClassName( $gameId ) );
    }

    public function load( string $gameId, Sudoku $sudoku )
    {
        $className = $this->getClassName( $gameId );
        $game = new $className();
        $clues = $game->getClues();

        for( $y = 1; $y <= 9; $y++ )
        {
            for( $x = 1; $x <= 9; $x++ )
            {
                $clue = $clues[ $y - 1 ][ $x - 1 ];

                if( $clue > 0 )
                {
                    $coordinates = new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) );
                    $cell = $sudoku->getCell( $coordinates );
                    $cell->setClue( new OneToNineValue( $clue ) );
                }
            }
        }
    }

    private function getClassName( int $gameId )
    {
        return 'XaviMontero\DrivewayOvershoot\Demo\Games\Game' . $gameId;
    }
}
