<?php

namespace XaviMontero\DrivewayOvershoot;

class Sudoku
{
    private $tiles;

    public function __construct()
    {
        $this->tiles = [];
        for( $y = 1; $y <= 9; $y++ )
        {
            $this->tiles[ $y ] = [];
            for( $x = 1; $x <= 9; $x++ )
            {
                $this->tiles[ $y ][ $x ] = new Tile();
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

    public function getTile( Coordinates $coordinates )
    {
        return $this->tiles[ $coordinates->getY() ][ $coordinates->getX() ];
    }
}
