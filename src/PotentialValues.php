<?php

namespace XaviMontero\DrivewayOvershoot;

class PotentialValues
{
    private $potential = [];

    public function __construct()
    {
        for( $i = 1; $i <= 9; $i++ )
        {
            $this->potential[ $i ] = true;
        }
    }

    public function getState() : PotentialValuesState
    {
        $count = 0;
        for( $i = 1; $i <= 9; $i++ )
        {
            if( $this->potential[ $i ] )
            {
                $count++;
            }
        }

        switch( $count )
        {
            case 0:

                $result = PotentialValuesState::Empty();
                break;

            case 1:

                $result = PotentialValuesState::Single();
                break;

            case 9:

                $result = PotentialValuesState::Full();
                break;

            default:

                $result = PotentialValuesState::Semi();
                break;
        }

        return $result;
    }

    public function isOption( Value $value ) : bool
    {
        return $this->potential[ $value->getValue() ];
    }

    public function killOption( Value $value )
    {
        $this->potential[ $value->getValue() ] = false;
    }

    public function getSingleOption() : Value
    {
        $state = $this->getState();
        if( $state != PotentialValuesState::Single() )
        {
            throw new \LogicException( "getSingleValue can only be called when the state is PotentialValuesState::Single, state " . $state . " found." );
        }

        return new Value( array_search( true, $this->potential ) );
    }
}
