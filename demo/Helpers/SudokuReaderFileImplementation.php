<?php

namespace DrivewayOvershoot\Demo\Helpers;

class SudokuReaderFileImplementation
{
    public function gameExists( $gameId )
    {
        return class_exists( 'DrivewayOvershoot\Demo\Games\Game' . $gameId );
    }
}
