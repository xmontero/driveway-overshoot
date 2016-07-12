<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Helpers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Demo\Games\GameInterface;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;

class SudokuLoaderFileImplementation implements SudokuLoaderInterface
{
    private $clues;

    public function gameExists( int $gameId )
    {
        return class_exists( $this->getClassName( $gameId ) );
    }

    public function loadClues( string $gameId )
    {
        $game = $this->getGame( $gameId );
        $this->clues = $game->getClues();
    }

    public function hasClue( Coordinates $coordinates ) : bool
    {
        $clueValue = $this->getClueValue( $coordinates );

        return ( $clueValue > 0 );
    }

    public function getClue( Coordinates $coordinates ) : OneToNineValue
    {
        $clueValue = $this->getClueValue( $coordinates );

        return new OneToNineValue( $clueValue );
    }

    //-- Private ----------------------------------------------------------//

    private function getClueValue( Coordinates $coordinates ) : int
    {
        $column = $coordinates->getColumnId()->getValue();
        $row = $coordinates->getRowId()->getValue();

        return $this->clues[ $row - 1 ][ $column - 1 ];
    }

    private function getGame( int $gameId ) : GameInterface
    {
        $className = $this->getClassName( $gameId );
        $game = new $className();

        return $game;
    }

    private function getClassName( int $gameId )
    {
        return 'XaviMontero\DrivewayOvershoot\Demo\Games\Game' . $gameId;
    }
}
