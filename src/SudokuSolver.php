<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Service that represents the algorhythm to solve a Sudoku.
 */
class SudokuSolver
{
    const MAX_ITERATIONS = 100;

    private $grid;

    public function __construct( Grid $grid )
    {
        $this->grid = $grid;
    }

    public function isSolved()
    {
        $solved = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $positionId = new OneToNineValue( $i );

            $rowUnit = $this->grid->getRowUnit( $positionId );
            $columnUnit = $this->grid->getColumnUnit( $positionId );
            $boxUnit = $this->grid->getBoxUnit( $positionId );

            $rowIsPerfect = $rowUnit->isPerfect();
            $columnIsPerfect = $columnUnit->isPerfect();
            $boxIsPerfect = $boxUnit->isPerfect();

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
        $ctr = 0;
        while( ! $this->isSolved() )
        {
            $this->scan();

            $ctr++;
            if( $ctr > self::MAX_ITERATIONS )
            {
                throw new \Exception( "Too many iterations in solver" );
            }
        }
    }

    public function scan()
    {
        $this->resetAllCandidates();
        $this->killAllCandidatesThatAppearAsValuesInConflictingCells();
        $this->setValuesForCellsWithSingleCandidate();
    }

    //-- Private ----------------------------------------------------------//

    private function resetAllCandidates()
    {
        for( $row = 1; $row <= 9; $row++ )
        {
            for( $column = 1; $column <= 9; $column++ )
            {
                $cell = $this->grid->getCell( new Coordinates( new OneToNineValue( $column ), new OneToNineValue( $row ) ) );
                $candidates = $cell->getCandidates();
                $candidates->reset();
            }
        }
    }

    private function killAllCandidatesThatAppearAsValuesInConflictingCells()
    {
        for( $rowIdValue = 1; $rowIdValue <= 9; $rowIdValue++ )
        {
            for( $columnIdValue = 1; $columnIdValue <= 9; $columnIdValue++ )
            {
                $columnId = new OneToNineValue( $columnIdValue );
                $rowId = new OneToNineValue( $rowIdValue );

                $cell = $this->grid->getCell( new Coordinates( $columnId, $rowId ) );
                $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingRowColumnOrBox( $cell );
            }
        }
    }

    private function killCandidatesOfGivenCellThatAppearAsValuesInConflictingRowColumnOrBox( Cell $cell )
    {
        $coordinates = $cell->getCoordinates();
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingRow( $coordinates->getRowId(), $cell );
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingColumn( $coordinates->getColumnId(), $cell );
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingBox( $coordinates->getBoxId(), $cell );
    }

    private function killCandidatesOfGivenCellThatAppearAsValuesInConflictingRow( OneToNineValue $rowId, Cell $exploredCell )
    {
        $row = $this->grid->getRowUnit( $rowId );
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingUnit( $row, $exploredCell );
    }

    private function killCandidatesOfGivenCellThatAppearAsValuesInConflictingColumn( OneToNineValue $columnId, Cell $exploredCell )
    {
        $column = $this->grid->getColumnUnit( $columnId );
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingUnit( $column, $exploredCell );
    }

    private function killCandidatesOfGivenCellThatAppearAsValuesInConflictingBox( OneToNineValue $boxId, Cell $exploredCell )
    {
        $box = $this->grid->getBoxUnit( $boxId );
        $this->killCandidatesOfGivenCellThatAppearAsValuesInConflictingUnit( $box, $exploredCell );
    }

    private function killCandidatesOfGivenCellThatAppearAsValuesInConflictingUnit( Unit $unit, Cell $exploredCell )
    {
        foreach( $unit->getCellsAsArray() as $conflictingCell )
        {
            if( $conflictingCell !== $exploredCell )
            {
                if( $conflictingCell->hasValue() )
                {
                    $conflictingValue = $conflictingCell->getValue();
                    $exploredCell->getCandidates()->killOption( $conflictingValue );
                }
            }
        }
    }

    private function setValuesForCellsWithSingleCandidate()
    {
        for( $rowValue = 1; $rowValue <= 9; $rowValue++ )
        {
            for( $columnValue = 1; $columnValue <= 9; $columnValue++ )
            {
                $column = new OneToNineValue( $columnValue );
                $row = new OneToNineValue( $rowValue );

                $cell = $this->grid->getCell( new Coordinates( $column, $row ) );

                if( ! $cell->hasValue() )
                {
                    $cell->setSolutionFromSingleCandidateIfPossible();
                }
            }
        }
    }

    private function printSudoku()
    {
        echo PHP_EOL;
        echo PHP_EOL . '+-----------+-----------+-----------+-----------+-----------+-----------+-----------+-----------+-----------+' . PHP_EOL;

        for( $row = 1; $row <= 9; $row++ )
        {
            echo "| ";
            for( $column = 1; $column <= 9; $column++ )
            {
                $cell = $this->grid->getCell( new Coordinates( new OneToNineValue( $column ), new OneToNineValue( $row ) ) );
                $value = $cell->hasValue() ? $cell->getValue()->getValue() : ".";
                echo "    " . $value . "     | ";
            }
            echo PHP_EOL;

            echo "| ";
            for( $column = 1; $column <= 9; $column++ )
            {
                $cell = $this->grid->getCell( new Coordinates( new OneToNineValue( $column ), new OneToNineValue( $row ) ) );
                $candidates = $cell->getCandidates();

                for( $candidateId = 1; $candidateId <= 9; $candidateId++ )
                {
                    echo $candidates->isOption( new OneToNineValue( $candidateId ) ) ? $candidateId : ".";
                }

                echo " | ";
            }
            echo PHP_EOL . '+-----------+-----------+-----------+-----------+-----------+-----------+-----------+-----------+-----------+' . PHP_EOL;
        }

    }

    private function killAllOptionsButSolution( int $solutionValue, Cell $cell )
    {
        // TODO: Remove duplication from CellTest.php and Unit?Test?.php
        $candidates = $cell->getCandidates();

        for( $v = 1; $v <= 9; $v++ )
        {
            if( $v != $solutionValue )
            {
                $candidates->killOption( new OneToNineValue( $v ) );
            }
        }
    }
}
