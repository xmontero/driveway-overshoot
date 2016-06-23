<?php

namespace DrivewayOvershoot\Demo\Views;

class AnsiWidgets
{
    private $ansi;

    public function __construct( $ansi )
    {
        $this->ansi = $ansi;
    }

    public function header()
    {
        $reset = $this->ansi->reset();
        $green = $this->ansi->green();
        $yellow = $this->ansi->yellow();

        $message = $green . "+-----------------------------------+" . $reset . PHP_EOL .
            $green . "|" . $yellow . " driveway-overshoot                " . $green . "|" . $reset . PHP_EOL .
            $green . "+-----------------------------------+" . $reset . PHP_EOL .
            PHP_EOL;

        return $message;
    }

    public function error( $errorMessage, $expected, $actual )
    {
        $reset = $this->ansi->reset();
        $red = $this->ansi->red();

        $widget = $red . "ERROR:" . $reset . " " . $errorMessage . PHP_EOL .
            "        Expected: " . $expected . PHP_EOL .
            "        Actual:   " . $actual . PHP_EOL .
            PHP_EOL;

        return $widget;
    }

    public function usage( $command )
    {
        $reset = $this->ansi->reset();
        $red = $this->ansi->red();
        $blue = $this->ansi->blue();

        $widget = $blue . "SYNOPSIS" . $reset . "
        php $command game_id

" . $blue . "OPTIONS" . $reset . "
        game_id
            An integer representing the number of game to be solved. The game must exist in demo/Games/game_*.php

" . $blue . "EXAMPLES" . $reset . "
        php $command 1
            This will solve demo/Games/game_1.php 

        php $command 2
            This will solve demo/Games/game_2.php 

";

        return $widget;
    }
}
