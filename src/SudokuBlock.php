<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Value object that holds 9 references to cells that can be a row, a column or a box.
 *
 * It is identified by the held references, which point to entities, so the value can apparently change, but in fact it does not as the
 * reference points to the same object and therefore the reference itself has not changed.
 */
class SudokuBlock
{
    private $cells;

    public function __construct( Cell $cell1, Cell $cell2, Cell $cell3, Cell $cell4, Cell $cell5, Cell $cell6, Cell $cell7, Cell $cell8, Cell $cell9 )
    {
        $this->cells =
            [
                1 => $cell1,
                2 => $cell2,
                3 => $cell3,
                4 => $cell4,
                5 => $cell5,
                6 => $cell6,
                7 => $cell7,
                8 => $cell8,
                9 => $cell9,
            ];
    }

    public function isEmpty() : bool
    {
        $empty = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $cell = $this->cells[ $i ];
            if( $cell->hasValue() )
            {
                $empty = false;
                break;
            }
        }

        return $empty;
    }

    public function hasIncompatibleValues() : bool
    {
        $valueIsPresentByIndex = array_fill( 1, 9, false );
        $result = false;

        for( $cellIndex = 1; $cellIndex <= 9; $cellIndex++ )
        {
            $cell = $this->cells[ $cellIndex ];

            if( $cell->hasValue() )
            {
                $cellValue = $cell->getValue()->getValue();
                if( $valueIsPresentByIndex[ $cellValue ] )
                {
                    $result = true;
                    break;
                }
                else
                {
                    $valueIsPresentByIndex[ $cellValue ] = true;
                }
            }
        }

        return $result;
    }

    public function isPerfect() : bool
    {
        $valueIsPresentByIndex = array_fill( 1, 9, false );

        for( $cellIndex = 1; $cellIndex <= 9; $cellIndex++ )
        {
            $cell = $this->cells[ $cellIndex ];

            if( $cell->hasValue() )
            {
                $cellValue = $cell->getValue()->getValue();
                $valueIsPresentByIndex[ $cellValue ] = true;
            }
        }

        $result = ! in_array( false, $valueIsPresentByIndex, true );

        return $result;
    }

    //-- Cell management --------------------------------------------------//

    public function hasCell( Cell $desiredCell ) : bool
    {
        $found = false;

        for( $cellIndex = 1; $cellIndex <= 9; $cellIndex++ )
        {
            $exploredCell = $this->cells[ $cellIndex ];

            if( $exploredCell === $desiredCell )
            {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function getCell( OneToNineValue $position ) : Cell
    {
        return $this->cells[ $position->getValue() ];
    }

    public function getCellsAsArray() : array
    {
        $clonedArray = [];

        foreach( $this->cells as $cellId => $cell )
        {
            $clonedArray[ $cellId ] = $cell;
        }

        return $clonedArray;
    }

    //-- Specific cell incompatibility ------------------------------------//

    public function cellIsIncompatible( Cell $cellUnderTest ) : bool
    {
        if( ! $this->hasCell( $cellUnderTest ) )
        {
            throw new \LogicException( "Can't check incompatibility of a cell that does not exist in the block." );
        }

        return false;
    }
}
