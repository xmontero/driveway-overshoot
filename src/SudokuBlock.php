<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Value object that holds 9 references to tiles that can be a row, a column or a square.
 *
 * It is identified by the held references, which point to entities, so the value can apparently change, but in fact it does not as the
 * reference points to the same object and therefore the reference itself has not changed.
 */
class SudokuBlock
{
    private $tiles;

    public function __construct( Tile $tile1, Tile $tile2, Tile $tile3, Tile $tile4, Tile $tile5, Tile $tile6, Tile $tile7, Tile $tile8, Tile $tile9 )
    {
        $this->tiles =
            [
                1 => $tile1,
                2 => $tile2,
                3 => $tile3,
                4 => $tile4,
                5 => $tile5,
                6 => $tile6,
                7 => $tile7,
                8 => $tile8,
                9 => $tile9,
            ];
    }

    public function isEmpty() : bool
    {
        $empty = true;

        for( $i = 1; $i <= 9; $i++ )
        {
            $tile = $this->tiles[ $i ];
            if( $tile->hasValue() )
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

        for( $tileIndex = 1; $tileIndex <= 9; $tileIndex++ )
        {
            $tile = $this->tiles[ $tileIndex ];

            if( $tile->hasValue() )
            {
                $tileValue = $tile->getValue()->getValue();
                if( $valueIsPresentByIndex[ $tileValue ] )
                {
                    $result = true;
                    break;
                }
                else
                {
                    $valueIsPresentByIndex[ $tileValue ] = true;
                }
            }
        }

        return $result;
    }

    public function isPerfect() : bool
    {
        $valueIsPresentByIndex = array_fill( 1, 9, false );

        for( $tileIndex = 1; $tileIndex <= 9; $tileIndex++ )
        {
            $tile = $this->tiles[ $tileIndex ];

            if( $tile->hasValue() )
            {
                $tileValue = $tile->getValue()->getValue();
                $valueIsPresentByIndex[ $tileValue ] = true;
            }
        }

        $result = ! in_array( false, $valueIsPresentByIndex, true );

        return $result;
    }

    //-- Tile management --------------------------------------------------//

    public function hasTile( Tile $desiredTile ) : bool
    {
        $found = false;

        for( $tileIndex = 1; $tileIndex <= 9; $tileIndex++ )
        {
            $exploredTile = $this->tiles[ $tileIndex ];

            if( $exploredTile === $desiredTile )
            {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function getTile( OneToNineValue $position )
    {
        return $this->tiles[ $position->getValue() ];
    }

    //-- Specific tile incompatibility ------------------------------------//

    public function tileIsIncompatible( Tile $tileUnderTest ) : bool
    {
        if( ! $this->hasTile( $tileUnderTest ) )
        {
            throw new \LogicException( "Can't check incompatibility of a tile that does not exist in the block." );
        }

        return false;
    }
}
