<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents the set of possible values that a Tile may have as the solution.
 *
 * When the Tile is created without a problem value, this PotentialValues set is initialized with all the numbers from 1 to 9.
 * As soon as the Tile is contextualized inside a Sudoku, the neighbours with values in the same row, column and 3x3 square are killing
 * values in the PotentialValues set.
 * If a Tile gets out of potential values, the Sudoku is incompatible.
 * If a Tile gets killed all the potential values except for one, then this only surviving value is assigned to the solution of the Tile.
 * This solution value given a single potential value, is going to kill other potential values in other tiles, until all the tiles are solved.
 */
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

    /**
     * @return PotentialValuesState
     */
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

    /**
     * Checks if a specific value is a potential value within the solutions.
     * @param OneToNineValue $value
     * @return bool
     */
    public function isOption( OneToNineValue $value ) : bool
    {
        return $this->potential[ $value->getValue() ];
    }

    /**
     * Makes a value disappear from the potential values set.
     * @param OneToNineValue $value
     */
    public function killOption( OneToNineValue $value )
    {
        $this->potential[ $value->getValue() ] = false;
    }

    /**
     * This method can only be called when the state is PotentialValuesState::Single
     * Returns a value representing the single remaining potential value.
     * @return OneToNineValue
     */
    public function getSingleOption() : OneToNineValue
    {
        $state = $this->getState();
        if( $state != PotentialValuesState::Single() )
        {
            throw new \LogicException( "getSingleValue can only be called when the state is PotentialValuesState::Single, state " . $state . " found." );
        }

        return new OneToNineValue( array_search( true, $this->potential ) );
    }
}
