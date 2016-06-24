<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Helpers;

class SudokuLoaderFileImplementation
{
    public function gameExists( int $gameId )
    {
        return class_exists( 'XaviMontero\DrivewayOvershoot\Demo\Games\Game' . $gameId );
    }
}
