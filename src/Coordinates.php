<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

/**
 * Value object that represents the coordinates of a Tile inside a 9x9 Sudoku.
 *
 * It is identified by the column and the row ids.
 */
class Coordinates
{
    private $columnId;
    private $rowId;

    public function __construct( OneToNineValue $columnId, OneToNineValue $rowId )
    {
        $this->columnId = $columnId;
        $this->rowId = $rowId;
    }

    /**
     * Returns the id of the column where coordinates belong to.
     * @return OneToNineValue
     */
    public function getColumnId() : OneToNineValue
    {
        return $this->columnId;
    }

    /**
     * Returns the id of the row where coordinates belong to.
     * @return OneToNineValue
     */
    public function getRowId() : OneToNineValue
    {
        return $this->rowId;
    }

    /**
     * Returns the id of the 3x3 square where the coordinates belong to.
     * Squares are numbered 123 in the first three rows, 456 in the three middle rows and 789 in the last three rows.
     * @return OneToNineValue
     */
    public function getSquareId() : OneToNineValue
    {
        $tileXBaseOne = $this->getColumnId()->getValue();
        $tileYBaseOne = $this->getRowId()->getValue();

        $tileXBaseZero = $tileXBaseOne - 1;
        $tileYBaseZero = $tileYBaseOne - 1;

        $squareXBaseZero = intdiv( $tileXBaseZero, 3 );
        $squareYBaseZero = intdiv( $tileYBaseZero, 3 );

        $squareId = 1 + $squareXBaseZero + $squareYBaseZero * 3;

        return new OneToNineValue( $squareId );
    }
}
