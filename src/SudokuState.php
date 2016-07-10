<?php

namespace XaviMontero\DrivewayOvershoot;

use MyCLabs\Enum\Enum;

/**
 * Enum of the possible states of the Sudoku.
 */
class SudokuState extends Enum
{
    const Editable = 0;
    const Resolved = 1;
}
