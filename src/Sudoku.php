<?php

namespace XaviMontero\DrivewayOvershoot;

class Sudoku
{
    /*
    private $tiles;

    public function __construct()
    {
        $this->tiles = array();
    }
*/
    public function isEmpty() : bool
    {
        return true;
    }

    /*
    public function setInitialValue( int $x, int $y, int $value )
    {
        $this->getTile( $x, $y )->setValue( $value );
    }*/

    public function getTile( int $x, int $y )
    {
        return new Tile();
    }
}
