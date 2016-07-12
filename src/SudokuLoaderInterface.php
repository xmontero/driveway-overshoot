<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Interface that provides a way for the SudokuFactory to load games into the newly created Sudoku.
 */
interface SudokuLoaderInterface
{
    public function hasClue( Coordinates $coordinates ) : bool;
    public function getClue( Coordinates $coordinates ) : OneToNineValue;
}
