<?php

namespace DrivewayOvershoot\Demo\Views;

class ErrorView
{
    private $ansi;

    public function __construct( $ansi )
    {
        $this->ansi = $ansi;
    }

    public function renderInvalidNumberOfArguments( $command, $numberOfArguments )
    {
        $errorMessage = "Invalid number of arguments." . PHP_EOL .
            "Expected: 1" . PHP_EOL .
            "Actual:   $numberOfArguments";

        return( $this->renderGenericErrorMessage( $command, $errorMessage ) );
    }

    public function renderArgumentIsNotInteger( $command, $gameId )
    {
        $errorMessage = "Invalid argument type for game_id." . PHP_EOL .
            "Expected: {integer}" . PHP_EOL .
            "Actual:   '$gameId'";

        return( $this->renderGenericErrorMessage( $command, $errorMessage ) );
    }

    private function renderGenericErrorMessage( $command, $errorMessage )
    {
        $reset = $this->ansi->reset();
        $red = $this->ansi->red();
        $green = $this->ansi->green();
        $yellow = $this->ansi->yellow();
        $blue = $this->ansi->blue();

        return(
            $green . "+-----------------------------------+" . $reset . "
" . $green . "|" . $yellow . " driveway-overshoot                " . $green . "|" . $reset . "
" . $green . "+-----------------------------------+" . $reset . "

" . $red . "ERROR:" . $reset . " $errorMessage

" . $blue . "SYNOPSIS" . $reset . "
        php $command game_id

" . $blue . "OPTIONS" . $reset . "
        game_id
            An integer representing the number of game to be solved. The game must exist in demo/Games/game_*.php

" . $blue . "EXAMPLES" . $reset . "
        php $command 1
            This will solve demo/Games/game_1.php 

        php $command 2
            This will solve demo/Games/game_2.php 

"
        );
    }
}
