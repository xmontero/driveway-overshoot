<?php

namespace XaviMontero\DrivewayOvershoot;

use MyCLabs\Enum\Enum;

/**
 * Enum that represents the state of a given Candidates set.
 */
class CandidatesState extends Enum
{
    /**
     * All the candidates in the set have been killed, so the Cell and therefore the Sudoku are incompatible.
     */
    const Empty = 0;

    /**
     * The Cell has been solved, as there is only one single value present within the candidates set.
     */
    const Single = 1;

    /**
     * Some candidates are present. In progress of solving.
     */
    const Semi = 2;

    /**
     * All candidates are present. The Cell has no clue on how to solve it.
     */
    const Full = 9;
}
