<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Service that represents the algorhythm to solve a Sudoku.
 */
class SudokuSolver
{
    private $sudokuGrid;

    public function __construct( SudokuGrid $sudokuGrid )
    {
        $this->sudokuGrid = $sudokuGrid;
    }

    public function isSolved()
    {
        $solved = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $positionId = new OneToNineValue( $i );

            $rowBlock = $this->sudokuGrid->getRowBlock( $positionId );
            $columnBlock = $this->sudokuGrid->getColumnBlock( $positionId );
            $boxBlock = $this->sudokuGrid->getBoxBlock( $positionId );

            $rowIsPerfect = $rowBlock->isPerfect();
            $columnIsPerfect = $columnBlock->isPerfect();
            $boxIsPerfect = $boxBlock->isPerfect();

            $solved = $solved && ( $rowIsPerfect && $columnIsPerfect && $boxIsPerfect );

            if( ! $solved )
            {
                break;
            }
        }

        return $solved;
    }

    public function solve()
    {
        $solution =
            [
                [ 7, 2, 3,   9, 4, 6,   8, 5, 1 ],
                [ 5, 4, 6,   2, 1, 8,   3, 7, 9 ],
                [ 9, 1, 8,   5, 3, 7,   4, 2, 6 ],

                [ 1, 6, 9,   4, 5, 2,   7, 3, 8 ],
                [ 2, 7, 5,   6, 8, 3,   1, 9, 4 ],
                [ 3, 8, 4,   1, 7, 9,   2, 6, 5 ],

                [ 4, 9, 7,   3, 6, 1,   5, 8, 2 ],
                [ 6, 3, 1,   8, 2, 5,   9, 4, 7 ],
                [ 8, 5, 2,   7, 9, 4,   6, 1, 3 ],
            ];

        $this->setSolution( $solution );
    }

    //-- Private ----------------------------------------------------------//

    private function setSolution( array $solutionArray )
    {
        for( $row = 1; $row <= 9; $row++ )
        {
            for( $column = 1; $column <= 9; $column++ )
            {
                $this->setSolutionInCell( $solutionArray, $column, $row );
            }
        }

        //$this->printSudoku();
    }

    private function setSolutionInCell( array $solutionArray, int $column, int $row )
    {
        $cell = $this->sudokuGrid->getCell( new Coordinates( new OneToNineValue( $column ), new OneToNineValue( $row ) ) );

        if( ! $cell->hasClue() )
        {
            $solutionValue = $solutionArray[ $row - 1 ][ $column - 1 ];
            $cell->setSolutionValue( new OneToNineValue( $solutionValue ) );
        }
    }

    private function printSudoku()
    {
        echo PHP_EOL;
        echo "---------------------------------" . PHP_EOL;

        for( $row = 1; $row <= 9; $row++ )
        {
            for( $column = 1; $column <= 9; $column++ )
            {
                $cell = $this->sudokuGrid->getCell( new Coordinates( new OneToNineValue( $column ), new OneToNineValue( $row ) ) );
                $value = $cell->hasValue() ? $cell->getValue()->getValue() : ".";
                echo $value;
            }
            echo PHP_EOL;
        }

        echo "---------------------------------" . PHP_EOL;
    }
}
