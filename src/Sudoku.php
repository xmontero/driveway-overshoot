<?php

namespace XaviMontero\DrivewayOvershoot;

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
                $this->tiles[ $y ][ $x ] = new Tile( $this, new Coordinates( $x, $y ) );
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
        return $this->tiles[ $coordinates->getY() ][ $coordinates->getX() ];
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

    public function checkIncompatibility( Coordinates $coordinates )
    {
        $result = ( ( $coordinates->getX() == 5 ) && ( $coordinates->getY() == 4 ) );

        return $result;
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
}
