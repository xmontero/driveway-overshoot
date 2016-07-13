<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Service that represents the algorhythm to solve a Sudoku.
 */
class SudokuSolver
{
    public function isSolved( SudokuGrid $sudoku )
    {
        $solved = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $positionId = new OneToNineValue( $i );

            $rowBlock = $sudoku->getRowBlock( $positionId );
            $columnBlock = $sudoku->getColumnBlock( $positionId );
            $boxBlock = $sudoku->getBoxBlock( $positionId );

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
}
