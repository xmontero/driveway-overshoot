<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Views;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\Tile;

class AnsiWidgets
{
    private $ansi;

    public function __construct( $ansi )
    {
        $this->ansi = $ansi;
    }

    public function header() : string
    {
        $reset = $this->ansi->reset();
        $green = $this->ansi->green();
        $yellow = $this->ansi->yellow();

        $message = $green . "+-----------------------------------+" . $reset . PHP_EOL .
            $green . "|" . $yellow . " driveway-overshoot                " . $green . "|" . $reset . PHP_EOL .
            $green . "+-----------------------------------+" . $reset . PHP_EOL .
            PHP_EOL;

        return $message;
    }

    public function error( string $errorMessage, string $expected, string $actual ) : string
    {
        $reset = $this->ansi->reset();
        $red = $this->ansi->red();

        $widget = $red . "ERROR:" . $reset . " " . $errorMessage . PHP_EOL .
            "        Expected: " . $expected . PHP_EOL .
            "        Actual:   " . $actual . PHP_EOL .
            PHP_EOL;

        return $widget;
    }

    public function usage( string $command ) : string
    {
        $synopsisContent = "        php $command gameId" . PHP_EOL;
        $widget = $this->block( 'SYNOPSIS', $synopsisContent );

        $optionsContent = "        gameId" . PHP_EOL;
        $optionsContent .= "            An integer representing the number of game to be solved. The game must exist in demo/Games/Game*.php where * is the gameId." . PHP_EOL;
        $widget .= $this->block( 'OPTIONS' , $optionsContent );

        $examplesContent = "        php $command 1" . PHP_EOL;
        $examplesContent .= "            This will solve demo/Games/Game1.php" . PHP_EOL . PHP_EOL;
        $examplesContent .= "        php $command 2" . PHP_EOL;
        $examplesContent .= "            This will solve demo/Games/Game2.php" . PHP_EOL;
        $widget .= $this->block( 'EXAMPLES' , $examplesContent );

        return $widget;
    }

    public function block( string $title, string $content ) : string
    {
        $reset = $this->ansi->reset();
        $blue = $this->ansi->blue();

        $widget = $blue . $title . $reset . PHP_EOL;
        $widget .= $content;
        $widget .= PHP_EOL;

        return $widget;
    }

    public function sudoku( string $mode, Sudoku $sudoku ) : string
    {
        $darkBlue = $this->ansi->darkBlue();
        $reset = $this->ansi->reset();

        $widget = '        ' . $darkBlue . "╔═══╤═══╤═══╦═══╤═══╤═══╦═══╤═══╤═══╗" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 1, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 2, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 3, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╠═══╪═══╪═══╬═══╪═══╪═══╬═══╪═══╪═══╣" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 4, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 5, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 6, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╠═══╪═══╪═══╬═══╪═══╪═══╬═══╪═══╪═══╣" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 7, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 8, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╟───┼───┼───╫───┼───┼───╫───┼───┼───╢" . $reset . PHP_EOL;
        $widget .= '        ' . $this->sudokuRow( $mode, 9, $sudoku ) . PHP_EOL;
        $widget .= '        ' . $darkBlue . "╚═══╧═══╧═══╩═══╧═══╧═══╩═══╧═══╧═══╝" . $reset . PHP_EOL;

        return $widget;
    }

    private function sudokuRow( string $mode, int $row, Sudoku $sudoku ) : string
    {
        $darkBlue = $this->ansi->darkBlue();
        $reset = $this->ansi->reset();

        $widget = $darkBlue . '║';
        $widget .= $this->sudokuRowTriad( $mode, 1, 2, 3, $row, $sudoku );
        $widget .= $this->sudokuRowTriad( $mode, 4, 5, 6, $row, $sudoku );
        $widget .= $this->sudokuRowTriad( $mode, 7, 8, 9, $row, $sudoku );
        $widget .= $reset;

        return $widget;
    }

    private function sudokuRowTriad( string $mode, int $x1, int $x2, int $x3, int $y, Sudoku $sudoku ) : string
    {
        $widget = $this->sudokuCellFromSudoku( $mode, $x1, $y, $sudoku, '│' );
        $widget .= $this->sudokuCellFromSudoku( $mode, $x2, $y, $sudoku, '│' );
        $widget .= $this->sudokuCellFromSudoku( $mode, $x3, $y, $sudoku, '║' );

        return $widget;
    }

    private function sudokuCellFromSudoku( string $mode, int $x, int $y, Sudoku $sudoku, $cellEnding ) : string
    {
        $tile = $sudoku->getTile( new Coordinates( $x, $y ) );
        return $this->sudokuCellFromTile( $mode, $tile, $cellEnding );
    }

    private function sudokuCellFromTile( string $mode, Tile $tile, $cellEnding ) : string
    {
        $reset = $this->ansi->reset();
        $red = $this->ansi->red();
        $white = $this->ansi->white();
        $darkBlue = $this->ansi->darkBlue();

        if( $tile->hasInitialValue() )
        {
            $content = $red . $tile->getInitialValue()->getValue() . $reset;
        }
        else
        {
            if( ( $tile->hasSolutionValue() ) && ( $mode == 'solution' ) )
            {
                $content = $white . $tile->getSolutionValue()->getValue() . $reset;
            }
            else
            {
                $content = ' ';
            }
        }

        $widget = $reset . ' ' . $content . ' ' . $darkBlue . $cellEnding;

        return $widget;
    }
}
