<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents a cell inside the Sudoku board.
 *
 * The Cell knows its own coordinates within the puzzle, and holds a changing state that evolves over time, from not solved to solved.
 */
class Cell
{
    private $candidates;
    private $coordinates;
    private $sudoku;
    private $clue = null;
    private $solutionValue = null;

    public function __construct( Grid $sudoku, Coordinates $coordinates, OneToNineValue $clue = null )
    {
        $this->sudoku = $sudoku;
        $this->coordinates = $coordinates;
        $this->clue = $clue;
        $this->candidates = new Candidates;
    }

    //---------------------------------------------------------------------//
    // Public                                                              //
    //---------------------------------------------------------------------//

    public function getCandidates() : Candidates
    {
        return $this->candidates;
    }

    //-- Clue -------------------------------------------------------------//

    public function getClue() : OneToNineValue
    {
        if( ! $this->hasClue() )
        {
            throw new \LogicException( "Can't get the clue if it was not set. Check hasClue() first." );
        }

        return $this->clue;
    }

    public function hasClue() : bool
    {
        return ( ! is_null( $this->clue ) );
    }

    //-- Solution Value ---------------------------------------------------//

    public function setSolutionFromSingleCandidateIfPossible()
    {
        $candidates = $this->getCandidates();

        if( $candidates->getState() == CandidatesState::Single() )
        {
            $candidateValue = $candidates->getSingleOption();
            $this->setSolutionValue( $candidateValue );
        }
    }

    public function getSolutionValue() : OneToNineValue
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

    public function getValue() : OneToNineValue
    {
        if( $this->hasClue() )
        {
            $result = $this->clue;
        }
        else
        {
            if( $this->hasSolutionValue() )
            {
                $result = $this->solutionValue;
            }
            else
            {
                throw new \LogicException( "Can't get the value if neither the clue or the solution were set. Check hasValue() first." );
            }
        }

        return $result;
    }

    public function hasValue() : bool
    {
        $hasClue = $this->hasClue();
        $hasSolutionValue = $this->hasSolutionValue();

        return ( $hasClue || $hasSolutionValue );
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

    //---------------------------------------------------------------------//
    // Private                                                             //
    //---------------------------------------------------------------------//

    private function setSolutionValue( OneToNineValue $value )
    {
        if( $this->hasClue() )
        {
            throw new \LogicException( "Can't set a solution on a cell that already contains an clue." );
        }

        $this->solutionValue = $value;
    }
}
