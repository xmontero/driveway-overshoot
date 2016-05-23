<?php

namespace XaviMontero\DrivewayOvershoot;

class PotentialValues
{
    private $killed = false;

    public function getState() : PotentialValuesState
    {
        $result = $this->killed ? PotentialValuesState::Semi() : PotentialValuesState::Full();
        return $result;
    }

    public function isOption( Value $value ) : bool
    {
        return true;
    }

    public function killOption( Value $value )
    {
        $this->killed = true;
    }
}
