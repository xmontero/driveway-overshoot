<?php

namespace XaviMontero\DrivewayOvershoot;

class PotentialValues
{
    public function getState() : PotentialValuesState
    {
        return PotentialValuesState::Full();
    }
}
