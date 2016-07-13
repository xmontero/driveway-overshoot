<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents the sudoku board with 81 cells in it, in a 9x9 arrangement.
 *
 * The cells can be prepared with problem numbers, or be empty.
 * The class holds intermediate states needed to solve the game, from the problem to the solution. For example, the cells can hold the potential values in function of the neighbours.
 */
class SudokuGrid
{
    private $observers = array();
    private $editable = true;
    private $cells;
    private $loader;

    public function __construct( SudokuLoaderInterface $loader )
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

    public function isEditable() : bool
    {
        return $this->editable;
    }

    public function setEditable( bool $editable )
    {
        if( $this->editable != $editable )
        {
            $this->editable = $editable;
            $this->raiseOnEditableChanged( $this->editable );
        }
    }

    public function getState() : SudokuState
    {
        $result = $this->editable ? SudokuState::Editable() : SudokuState::Resolved();
        return $result;
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

    public function getRowBlockByCell( Cell $cell ) : SudokuBlock
    {
        return $this->getRowBlock( $cell->getCoordinates()->getRowId() );
    }

    public function getRowBlock( OneToNineValue $y ) : SudokuBlock
    {
        $cell = [];

        for( $x = 1; $x <= 9; $x++ )
        {
            $cell[ $x ] = $this->getCell( new Coordinates( new OneToNineValue( $x ), $y ) );
        }

        $block = new SudokuBlock( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $block;
    }

    public function getColumnBlock( OneToNineValue $x ) : SudokuBlock
    {
        $cell = [];

        for( $y = 1; $y <= 9; $y++ )
        {
            $cell[ $y ] = $this->getCell( new Coordinates( $x, new OneToNineValue( $y ) ) );
        }

        $block = new SudokuBlock( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $block;
    }

    public function getBoxBlock( OneToNineValue $blockId ) : SudokuBlock
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

        $blockRow = intdiv( $blockId->getValue() - 1, 3 );
        $blockColumn = ( $blockId->getValue() - 1 ) % 3;

        $cell = [];

        for( $inBlockY = 0; $inBlockY < 3; $inBlockY++ )
        {
            for( $inBlockX = 0; $inBlockX < 3; $inBlockX++ )
            {
                $x = $blockColumn * 3 + $inBlockX + 1;
                $y = $blockRow * 3 + $inBlockY + 1;
                $positionInBlock = $inBlockY * 3 + $inBlockX + 1;

                $cell[ $positionInBlock ] = $this->getCell( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
            }
        }

        $block = new SudokuBlock( $cell[ 1 ], $cell[ 2 ], $cell[ 3 ], $cell[ 4 ], $cell[ 5 ], $cell[ 6 ], $cell[ 7 ], $cell[ 8 ], $cell[ 9 ] );

        return $block;
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
            $row = $this->getRowBlock( new OneToNineValue( $y ) );
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
            $column = $this->getColumnBlock( new OneToNineValue( $x ) );
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
            $box = $this->getBoxBlock( new OneToNineValue( $i ) );
            if( $box->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }
}
