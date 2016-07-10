<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Entity that represents a cell inside the Sudoku board.
 *
 * The Cell knows its own coordinates within the puzzle, and holds a changing state that evolves over time, from not solved to solved.
 */
class Cell
{
    private $coordinates;
    private $sudoku;
    private $clue = null;
    private $solutionValue = null;

    public function __construct( Sudoku $sudoku, Coordinates $coordinates )
    {
        $this->coordinates = $coordinates;
        $this->sudoku = $sudoku;
    }

    public function isEmpty() : bool
    {
        return is_null( $this->clue );
    }

    public function getPotentialValues() : PotentialValues
    {
        return new PotentialValues;
    }

    //-- Clue -------------------------------------------------------------//

    public function setClue( OneToNineValue $value )
    {
        if( $this->hasSolutionValue() )
        {
            throw new \LogicException( "Can't set an clue on a cell that already contains an solution value." );
        }

        $this->clue = $value;
    }

    public function removeClue()
    {
        $this->clue = null;
    }

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

    public function setSolutionValue( OneToNineValue $value )
    {
        if( $this->hasClue() )
        {
            throw new \LogicException( "Can't set a solution on a cell that already contains an clue." );
        }

        $this->solutionValue = $value;
    }

    public function removeSolutionValue()
    {
        $this->solutionValue = null;
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
}
