<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents the sudoku board with 81 cells in it, in a 9x9 arrangement.
 *
 * The cells can be prepared with problem numbers, or be empty.
 * The class holds intermediate states needed to solve the game, from the problem to the solution. For example, the cells can hold the candidates in function of the neighbours.
 */
class Grid
{
    private $observers = array();
    private $editable = true;
    private $cells;
    private $loader;

    public function __construct( GridLoaderInterface $loader )
    {
        $this->loader = $loader;

        $this->cells = [];
        for( $y = 1; $y <= 9; $y++ )
        {
            $this->cells[ $y ] = [];
            for( $x = 1; $x <= 9; $x++ )
            {
                $columnId = new OneToNineValue( $x );
                $rowId = new OneToNineValue( $y );
                $coordinates = new Coordinates( $columnId, $rowId );

                $clue = $loader->hasClue( $coordinates ) ? $loader->getClue( $coordinates ) : null;

                $cell = new Cell( $this, $coordinates, $clue );
                $this->cells[ $y ][ $x ] = $cell;
            }
        }
    }

    public function getCell( Coordinates $coordinates ) : Cell
    {
        $columnId = $coordinates->getColumnId();
        $rowId = $coordinates->getRowId();

        return $this->cells[ $rowId->getValue() ][ $columnId->getValue() ];
    }

    public function addObserver( SudokuObserverInterface $observer )
    {
        $this->observers[] = $observer;
    }

    //-- Incompatibility --------------------------------------------------//

    public function checkIncompatibility( Coordinates $coordinates ) : bool
    {
        $columnId = $coordinates->getColumnId();
        $rowId = $coordinates->getRowId();

        $result = ( ( $columnId->getValue() == 1 ) && ( $rowId->getValue() == 4 ) );

        return $result;
    }

    public function hasIncompatibleClues() : bool
    {
        $hasIncompatibleCluesInRow = $this->hasIncompatibleCluesInRow();
        $hasIncompatibleCluesInColumn = $this->hasIncompatibleCluesInColumn();
        $hasIncompatibleCluesInBox = $this->hasIncompatibleCluesInBox();

        $hasIncompatibleClues = $hasIncompatibleCluesInRow || $hasIncompatibleCluesInColumn || $hasIncompatibleCluesInBox;

        return $hasIncompatibleClues;
    }

    public function getRowUnitByCell( Cell $cell ) : Unit
    {
        return $this->getRowUnit( $cell->getCoordinates()->getRowId() );
    }

    public function getRowUnit( OneToNineValue $rowId ) : Unit
    {
        $cell = [];

        for( $columnIdValue = 1; $columnIdValue <= 9; $columnIdValue++ )
        {
            $cell[ $columnIdValue ] = $this->getCell( new Coordinates( new OneToNineValue( $columnIdValue ), $rowId ) );
        }

        $unit = new Unit( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $unit;
    }

    public function getColumnUnit( OneToNineValue $rowId ) : Unit
    {
        $cell = [];

        for( $rowIdValue = 1; $rowIdValue <= 9; $rowIdValue++ )
        {
            $cell[ $rowIdValue ] = $this->getCell( new Coordinates( $rowId, new OneToNineValue( $rowIdValue ) ) );
        }

        $unit = new Unit( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $unit;
    }

    public function getBoxUnit( OneToNineValue $unitId ) : Unit
    {
        // Boxes are numbered from rows top to bottom and, inside each row, from left to right, from 1 to 9.
        // Below, the numbers represent the number of the box the cell belongs to.
        //
        // [ 1, 1, 1,   2, 2, 2,   3, 3, 3 ],
        // [ 1, 1, 1,   2, 2, 2,   3, 3, 3 ],
        // [ 1, 1, 1,   2, 2, 2,   3, 3, 3 ],
        //
        // [ 4, 4, 4,   5, 5, 5,   6, 6, 6 ],
        // [ 4, 4, 4,   5, 5, 5,   6, 6, 6 ],
        // [ 4, 4, 4,   5, 5, 5,   6, 6, 6 ],
        //
        // [ 7, 7, 7,   8, 8, 8,   9, 9, 9 ],
        // [ 7, 7, 7,   8, 8, 8,   9, 9, 9 ],
        // [ 7, 7, 7,   8, 8, 8,   9, 9, 9 ],

        $unitRow = intdiv( $unitId->getValue() - 1, 3 );
        $unitColumn = ( $unitId->getValue() - 1 ) % 3;

        $cell = [];

        for( $inUnitY = 0; $inUnitY < 3; $inUnitY++ )
        {
            for( $inUnitX = 0; $inUnitX < 3; $inUnitX++ )
            {
                $x = $unitColumn * 3 + $inUnitX + 1;
                $y = $unitRow * 3 + $inUnitY + 1;
                $positionInUnit = $inUnitY * 3 + $inUnitX + 1;

                $cell[ $positionInUnit ] = $this->getCell( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
            }
        }

        $unit = new Unit( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $unit;
    }


    //---------------------------------------------------------------------//
    // Private                                                             //
    //---------------------------------------------------------------------//

    private function raiseOnEditableChanged( bool $editable )
    {
        foreach( $this->observers as $observer )
        {
            $observer->onEditableChanged( $editable );
        }
    }

    private function hasIncompatibleCluesInRow() : bool
    {
        $incompatible = false;

        for( $y = 1; $y <= 9; $y++ )
        {
            $row = $this->getRowUnit( new OneToNineValue( $y ) );
            if( $row->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }

    private function hasIncompatibleCluesInColumn() : bool
    {
        $incompatible = false;

        for( $x = 1; $x <= 9; $x++ )
        {
            $column = $this->getColumnUnit( new OneToNineValue( $x ) );
            if( $column->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }

    private function hasIncompatibleCluesInBox() : bool
    {
        $incompatible = false;

        for( $i = 1; $i <= 9; $i++ )
        {
            $box = $this->getBoxUnit( new OneToNineValue( $i ) );
            if( $box->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }
}
