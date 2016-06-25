<?php

namespace XaviMontero\DrivewayOvershoot;

class Tile
{
    private $initialValue = null;

    public function isEmpty() : bool
    {
        return is_null( $this->initialValue );
    }

    public function getPotentialValues() : PotentialValues
    {
        return new PotentialValues;
    }

    public function setInitialValue( Value $value )
    {
        $this->initialValue = $value;
    }

    public function hasInitialValue() : bool
    {
        return ( ! is_null( $this->initialValue ) );
    }

    public function getInitialValue() : Value
    {
        return $this->initialValue;
    }
}
