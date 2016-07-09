<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

/**
 * Represents the position of a Tile inside a Sudoku.
 *
 * This is a value object that represents the coordinates inside a 9x9 Sudoku.
 * It is identified by the column and the row ids.
 */
class Coordinates
{
    private $column;
    private $row;

    public function __construct( OneToNineValue $column, OneToNineValue $row )
    {
        $this->column = $column;
        $this->row = $row;
    }

    /**
     * Returns the id of the column where coordinates belong to.
     * @return \XaviMontero\DrivewayOvershoot\OneToNineValue
     */
    public function getColumn() : OneToNineValue
    {
        return $this->column;
    }

    /**
     * Returns the id of the row where coordinates belong to.
     * @return \XaviMontero\DrivewayOvershoot\OneToNineValue
     */
    public function getRow() : OneToNineValue
    {
        return $this->row;
    }

    /**
     * Returns the id of the 3x3 square where the coordinates belong to.
     * Squares are numbered 123 in the first three rows, 456 in the three middle rows and 789 in the last three rows.
     * @return \XaviMontero\DrivewayOvershoot\OneToNineValue
     */
    public function getSquare() : OneToNineValue
    {
        $x = $this->getColumn()->getValue();
        $y = $this->getRow()->getValue();

        $squareX = intdiv( $x - 1, 3 ) + 1;
        $squareY = intdiv( $y - 1, 3 );

        $squareId = $squareX + $squareY * 3;

        return new OneToNineValue( $squareId );
    }
}
