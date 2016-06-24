<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Views;

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
        $synopsisContent = "        php $command gameId" . PHP_EOL;
        $widget = $this->block( 'SYNOPSIS', $synopsisContent );

        $optionsContent = "        gameId" . PHP_EOL;
        $optionsContent .= "            An integer representing the number of game to be solved. The game must exist in demo/Games/Game*.php where * is the gameId." . PHP_EOL;
        $widget .= $this->block( 'OPTIONS' , $optionsContent );

        $examplesContent = "        php $command 1" . PHP_EOL;
        $examplesContent .= "            This will solve demo/Games/Game1.php" . PHP_EOL . PHP_EOL;
        $examplesContent .= "        php $command 2" . PHP_EOL;
        $examplesContent .= "            This will solve demo/Games/Game2.php" . PHP_EOL;
        $widget .= $this->block( 'EXAMPLES' , $examplesContent );

        return $widget;
    }

    public function block( $title, $content )
    {
        $reset = $this->ansi->reset();
        $blue = $this->ansi->blue();

        $widget = $blue . $title . $reset . PHP_EOL;
        $widget .= $content;
        $widget .= PHP_EOL;

        return $widget;
    }
}
