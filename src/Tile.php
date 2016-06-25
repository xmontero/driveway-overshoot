<?php

namespace XaviMontero\DrivewayOvershoot;

class Tile
{
    private $initialValue = null;
    private $solutionValue = null;

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
}
