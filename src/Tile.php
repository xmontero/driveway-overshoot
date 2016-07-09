<?php

namespace XaviMontero\DrivewayOvershoot;

class Tile
{
    private $coordinates;
    private $sudoku;
    private $initialValue = null;
    private $solutionValue = null;

    public function __construct( Sudoku $sudoku, Coordinates $coordinates )
    {
        $this->coordinates = $coordinates;
        $this->sudoku = $sudoku;
    }

    public function isEmpty() : bool
    {
        return is_null( $this->initialValue );
    }

    public function getPotentialValues() : PotentialValues
    {
        return new PotentialValues;
    }

    //-- Initial Value ----------------------------------------------------//

    public function setInitialValue( Value $value )
    {
        if( $this->hasSolutionValue() )
        {
            throw new \LogicException( "Can't set an initial value on a tile that already contains an solution value." );
        }

        $this->initialValue = $value;
    }

    public function removeInitialValue()
    {
        $this->initialValue = null;
    }

    public function getInitialValue() : Value
    {
        if( ! $this->hasInitialValue() )
        {
            throw new \LogicException( "Can't get the initial value if it was not set. Check hasInitialValue() first." );
        }

        return $this->initialValue;
    }

    public function hasInitialValue() : bool
    {
        return ( ! is_null( $this->initialValue ) );
    }

    //-- Solution Value ---------------------------------------------------//

    public function setSolutionValue( Value $value )
    {
        if( $this->hasInitialValue() )
        {
            throw new \LogicException( "Can't set a solution on a tile that already contains an initial value." );
        }

        $this->solutionValue = $value;
    }

    public function removeSolutionValue()
    {
        $this->solutionValue = null;
    }

    public function getSolutionValue() : Value
    {
        if( ! $this->hasSolutionValue() )
        {
            throw new \LogicException( "Can't get the solution value if it was not set. Check hasSolutionValue() first." );
        }

        return $this->solutionValue;
    }

    public function hasSolutionValue() : bool
    {
        return ( ! is_null( $this->solutionValue ) );
    }

    //-- Generic Value Wrapper --------------------------------------------//

    public function getValue() : Value
    {
        if( $this->hasInitialValue() )
        {
            $result = $this->initialValue;
        }
        else
        {
            if( $this->hasSolutionValue() )
            {
                $result = $this->solutionValue;
            }
            else
            {
                throw new \LogicException( "Can't get the value if neither the initial or the solution were set. Check hasValue() first." );
            }
        }

        return $result;
    }

    public function hasValue() : bool
    {
        $hasInitialValue = $this->hasInitialValue();
        $hasSolutionValue = $this->hasSolutionValue();

        return ( $hasInitialValue || $hasSolutionValue );
    }

    public function hasIncompatibleValue() : bool
    {
        return $this->sudoku->checkIncompatibility( $this->coordinates );
    }

    //-- Coordinates ------------------------------------------------------//

    public function getCoordinates() : Coordinates
    {
        return $this->coordinates;
    }
}
