<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Factory service to create and load Sudoku objects.
 *
 * The loading takes place from an injected service implementing GridLoaderInterface which is able to provide data for the clues of the problem to be solved.
 */
class SudokuFactory
{
    private $loader;

    public function __construct( GridLoaderInterface $loader )
    {
        $this->loader = $loader;
    }

    public function createSudoku() : Grid
    {
        $sudoku = new Grid( $this->loader );
        return $sudoku;
    }
}
