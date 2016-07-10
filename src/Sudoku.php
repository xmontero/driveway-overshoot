<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents the sudoku board with 81 tiles in it, in a 9x9 arrangement.
 *
 * The tiles can be prepared with problem numbers, or be empty.
 * The class holds intermediate states needed to solve the game, from the problem to the solution. For example, the tiles can hold the potential values in function of the neighbours.
 */
class Sudoku
{
    private $observers = array();
    private $editable = true;
    private $tiles;

    public function __construct()
    {
        $this->tiles = [];
        for( $y = 1; $y <= 9; $y++ )
        {
            $this->tiles[ $y ] = [];
            for( $x = 1; $x <= 9; $x++ )
            {
                $this->tiles[ $y ][ $x ] = new Tile( $this, new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
            }
        }
    }

    public function isEmpty() : bool
    {
        $empty = true;

        for( $y = 1; $y <= 9; $y++ )
        {
            for( $x = 1; $x <= 9; $x++ )
            {
                $tile = $this->tiles[ $y ][ $x ];
                if( ! $tile->isEmpty() )
                {
                    $empty = false;
                    break;
                }
            }
        }

        return $empty;
    }

    public function getTile( Coordinates $coordinates ) : Tile
    {
        return $this->tiles[ $coordinates->getRow()->getValue() ][ $coordinates->getColumn()->getValue() ];
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
        $column = $coordinates->getColumn();
        $row = $coordinates->getRow();

        $result = ( ( $coordinates->getColumn()->getValue() == 1 ) && ( $coordinates->getRow()->getValue() == 4 ) );

        return $result;
    }

    public function hasIncompatibleInitialValues() : bool
    {
        $hasIncompatibleInitialValuesRow = $this->hasIncompatibleInitialValuesRow();
        $hasIncompatibleInitialValuesColumn = $this->hasIncompatibleInitialValuesColumn();
        $hasIncompatibleInitialValuesSquare = $this->hasIncompatibleInitialValuesSquare();

        $hasIncompatibleInitialValues = $hasIncompatibleInitialValuesRow || $hasIncompatibleInitialValuesColumn || $hasIncompatibleInitialValuesSquare;

        return $hasIncompatibleInitialValues;
    }

    public function getRowBlockByTile( Tile $tile ) : SudokuBlock
    {
        return $this->getRowBlock( $tile->getCoordinates()->getRow()->getValue() );
    }

    public function getRowBlock( int $y ) : SudokuBlock
    {
        $tile = [];

        for( $x = 1; $x <= 9; $x++ )
        {
            $tile[ $x ] = $this->getTile( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
        }

        $block = new SudokuBlock( $tile[ 1 ], $tile[ 2 ], $tile[ 3 ], $tile[ 4 ], $tile[ 5 ], $tile[ 6 ], $tile[ 7 ], $tile[ 8 ], $tile[ 9 ] );

        return $block;
    }

    public function getColumnBlock( int $x ) : SudokuBlock
    {
        $tile = [];

        for( $y = 1; $y <= 9; $y++ )
        {
            $tile[ $y ] = $this->getTile( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
        }

        $block = new SudokuBlock( $tile[ 1 ], $tile[ 2 ], $tile[ 3 ], $tile[ 4 ], $tile[ 5 ], $tile[ 6 ], $tile[ 7 ], $tile[ 8 ], $tile[ 9 ] );

        return $block;
    }

    public function getSquareBlock( int $blockNumber ) : SudokuBlock
    {
        // Squares are numbered from rows top to bottom and, inside each row, from left to right, from 1 to 9.
        // Below, the numbers represent the number of the square the tile belongs to.
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

        $blockRow = intdiv( $blockNumber - 1, 3 );
        $blockColumn = ( $blockNumber - 1 ) % 3;

        $tile = [];

        for( $inBlockY = 0; $inBlockY < 3; $inBlockY++ )
        {
            for( $inBlockX = 0; $inBlockX < 3; $inBlockX++ )
            {
                $x = $blockColumn * 3 + $inBlockX + 1;
                $y = $blockRow * 3 + $inBlockY + 1;
                $positionInBlock = $inBlockY * 3 + $inBlockX + 1;

                $tile[ $positionInBlock ] = $this->getTile( new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) ) );
            }
        }

        $block = new SudokuBlock( $tile[ 1 ], $tile[ 2 ], $tile[ 3 ], $tile[ 4 ], $tile[ 5 ], $tile[ 6 ], $tile[ 7 ], $tile[ 8 ], $tile[ 9 ] );

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

    private function hasIncompatibleInitialValuesRow() : bool
    {
        $incompatible = false;

        for( $y = 1; $y <= 9; $y++ )
        {
            $row = $this->getRowBlock( $y );
            if( $row->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }

    private function hasIncompatibleInitialValuesColumn() : bool
    {
        $incompatible = false;

        for( $x = 1; $x <= 9; $x++ )
        {
            $column = $this->getColumnBlock( $x );
            if( $column->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }

    private function hasIncompatibleInitialValuesSquare() : bool
    {
        $incompatible = false;

        for( $i = 1; $i <= 9; $i++ )
        {
            $square = $this->getSquareBlock( $i );
            if( $square->hasIncompatibleValues() )
            {
                $incompatible = true;
                break;
            }
        }

        return $incompatible;
    }
}
