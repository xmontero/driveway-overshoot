<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Views;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\SudokuGrid;
use XaviMontero\DrivewayOvershoot\Cell;

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

    public function sudoku( string $mode, SudokuGrid $sudoku ) : string
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

    private function sudokuRow( string $mode, int $row, SudokuGrid $sudoku ) : string
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

    private function sudokuRowTriad( string $mode, int $x1, int $x2, int $x3, int $y, SudokuGrid $sudoku ) : string
    {
        $widget = $this->sudokuCellFromSudoku( $mode, $x1, $y, $sudoku, '│' );
        $widget .= $this->sudokuCellFromSudoku( $mode, $x2, $y, $sudoku, '│' );
        $widget .= $this->sudokuCellFromSudoku( $mode, $x3, $y, $sudoku, '║' );

        return $widget;
    }

    private function sudokuCellFromSudoku( string $mode, int $x, int $y, SudokuGrid $sudoku, $cellEnding ) : string
    {
        $cell = $sudoku->getCell( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
        return $this->sudokuCellFromCell( $mode, $cell, $cellEnding );
    }

    private function sudokuCellFromCell( string $mode, Cell $cell, $cellEnding ) : string
    {
        $reset = $this->ansi->reset();
        $darkBlue = $this->ansi->darkBlue();

        $dto = $this->sudokuCellFromCellDto( $mode, $cell );

        $color = $dto[ 'color' ];
        $value = $dto[ 'value' ];

        $widget = $reset . $color . ' ' . $value . ' ' . $reset . $darkBlue . $cellEnding;

        return $widget;
    }

    private function sudokuCellFromCellDto( string $mode, Cell $cell ) : array
    {
        switch( $mode )
        {
            case 'problem':

                $dto = $this->sudokuCellFromCellProblemDto( $cell );
                break;

            case 'solution':

                $dto = $this->sudokuCellFromCellSolutionDto( $cell );
                break;

            default:

                throw new \DomainException( "mode must be 'problem' or 'solution', $mode found." );
        }

        return $dto;
    }

    private function sudokuCellFromCellProblemDto( Cell $cell ) : array
    {
        $red = $this->ansi->red();

        $dto = [];

        $dto[ 'color' ] = $red;
        $dto[ 'value' ] = $cell->hasClue() ? $cell->getClue()->getValue() : ' ';

        return $dto;
    }

    private function sudokuCellFromCellSolutionDto( Cell $cell ) : array
    {
        $invertedRed = $this->ansi->invertedRed();
        $red = $this->ansi->red();
        $white = $this->ansi->white();
        $green = $this->ansi->green();

        $dto = [];

        $dto[ 'value' ] = $cell->hasValue() ? $cell->getValue()->getValue() : ' ';

        if( $cell->hasIncompatibleValue() )
        {
            $dto[ 'color' ] = $invertedRed;
        }
        else
        {
            if( $cell->hasClue() )
            {
                $dto[ 'color' ] = $red;
            }
            else
            {
                $dto[ 'color' ] = $white;
            }
        }

        return $dto;
    }
}
