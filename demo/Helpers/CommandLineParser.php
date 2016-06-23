<?php

namespace DrivewayOvershoot\Demo\Helpers;

class CommandLineParser
{
    private $argv;

    public function __construct( $argv )
    {
        $this->argv = $argv;
    }

    public function getCommand()
    {
        return $this->argv[ 0 ];
    }

    public function getNumberOfArguments()
    {
        return count( $this->argv ) - 1;
    }

    public function getGameId()
    {
        return $this->argv[ 1 ];
    }
}
