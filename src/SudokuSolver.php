<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Service that represents the algorhythm to solve a Sudoku.
 */
class SudokuSolver
{
    public function isSolved( Sudoku $sudoku )
    {
        $solved = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $positionId = new OneToNineValue( $i );

            $rowBlock = $sudoku->getRowBlock( $positionId );
            $columnBlock = $sudoku->getColumnBlock( $positionId );
            $squareBlock = $sudoku->getSquareBlock( $positionId );

            $rowIsPerfect = $rowBlock->isPerfect();
            $columnIsPerfect = $columnBlock->isPerfect();
            $squareIsPerfect = $squareBlock->isPerfect();

            $solved = $solved && ( $rowIsPerfect && $columnIsPerfect && $squareIsPerfect );

            if( ! $solved )
            {
                break;
            }
        }

        return $solved;
    }
}
