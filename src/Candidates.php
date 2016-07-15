<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents the set of possible values that a Cell may have as the solution.
 *
 * When the Cell is created without a problem value, this Candidates set is initialized with all the numbers from 1 to 9.
 * As soon as the Cell is contextualized inside a Sudoku, the neighbours with values in the same row, column and 3x3 box are killing
 * values in the Candidates set.
 * If a Cell gets out of candidates, the Sudoku is incompatible.
 * If a Cell gets killed all the candidates except for one, then this only surviving value is assigned to the solution of the Cell.
 * This solution value given a single candidates, is going to kill other candidates in other cells, until all the cells are solved.
 */
class Candidates
{
    private $candidates = [];

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Resets all candidates as potential values to be used for the cell.
     * @return void
     */
    public function reset()
    {
        for( $i = 1; $i <= 9; $i++ )
        {
            $this->candidates[ $i ] = true;
        }
    }

    /**
     * @return CandidatesState
     */
    public function getState() : CandidatesState
    {
        $count = 0;
        for( $i = 1; $i <= 9; $i++ )
        {
            if( $this->candidates[ $i ] )
            {
                $count++;
            }
        }

        switch( $count )
        {
            case 0:

                $result = CandidatesState::Empty();
                break;

            case 1:

                $result = CandidatesState::Single();
                break;

            case 9:

                $result = CandidatesState::Full();
                break;

            default:

                $result = CandidatesState::Semi();
                break;
        }

        return $result;
    }

    /**
     * Checks if a specific value is a candidates within the solutions.
     * @param OneToNineValue $value
     * @return bool
     */
    public function isOption( OneToNineValue $value ) : bool
    {
        return $this->candidates[ $value->getValue() ];
    }

    /**
     * Makes a value disappear from the candidates set.
     * @param OneToNineValue $value
     */
    public function killOption( OneToNineValue $value )
    {
        $this->candidates[ $value->getValue() ] = false;
    }

    /**
     * This method can only be called when the state is CandidatesState::Single
     * Returns a value representing the single remaining candidate.
     * @return OneToNineValue
     */
    public function getSingleOption() : OneToNineValue
    {
        $state = $this->getState();
        if( $state != CandidatesState::Single() )
        {
            throw new \LogicException( "getSingleValue can only be called when the state is CandidatesState::Single, state " . $state . " found." );
        }

        return new OneToNineValue( array_search( true, $this->candidates ) );
    }
}
