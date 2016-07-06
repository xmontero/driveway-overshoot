<?php

namespace XaviMontero\DrivewayOvershoot;

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

    public function hasTile( Tile $checkedTile )
    {
        $found = false;

        for( $tileIndex = 1; $tileIndex <= 9; $tileIndex++ )
        {
            $exploredTile = $this->tiles[ $tileIndex ];

            if( $exploredTile === $checkedTile )
            {
                $found = true;
                break;
            }
        }

        return $found;
    }
}
