<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

/**
 * Value object that represents the coordinates of a Cell inside a 9x9 Sudoku.
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
     * Returns the id of the 3x3 box where the coordinates belong to.
     * Boxes are numbered 123 in the first three rows, 456 in the three middle rows and 789 in the last three rows.
     * @return OneToNineValue
     */
    public function getBoxId() : OneToNineValue
    {
        $cellXBaseOne = $this->getColumnId()->getValue();
        $cellYBaseOne = $this->getRowId()->getValue();

        $cellXBaseZero = $cellXBaseOne - 1;
        $cellYBaseZero = $cellYBaseOne - 1;

        $boxXBaseZero = intdiv( $cellXBaseZero, 3 );
        $boxYBaseZero = intdiv( $cellYBaseZero, 3 );

        $boxId = 1 + $boxXBaseZero + $boxYBaseZero * 3;

        return new OneToNineValue( $boxId );
    }
}
