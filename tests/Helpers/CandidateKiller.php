<?php

namespace XaviMontero\DrivewayOvershoot\Tests\Helpers;

use XaviMontero\DrivewayOvershoot\Cell;
use XaviMontero\DrivewayOvershoot\OneToNineValue;

class CandidateKiller
{
    public static function killAllOptionsButSolutionFromCell( int $solutionValue, Cell $cell )
    {
        // TODO: Remove duplication from UnitTest.php
        $candidates = $cell->getCandidates();

        for( $v = 1; $v <= 9; $v++ )
        {
            if( $v != $solutionValue )
            {
                $candidates->killOption( new OneToNineValue( $v ) );
            }
        }
    }
}
