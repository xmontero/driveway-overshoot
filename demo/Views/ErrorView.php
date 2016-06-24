<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Views;

class ErrorView
{
    private $widgets;

    public function __construct( $widgets )
    {
        $this->widgets = $widgets;
    }

    public function renderInvalidNumberOfArguments( $command, $numberOfArguments )
    {
        return( $this->renderGenericErrorMessage( $command, "Invalid number of arguments.", 1, $numberOfArguments ) );
    }

    public function renderArgumentIsNotInteger( $command, $gameId )
    {
        return( $this->renderGenericErrorMessage( $command, "Invalid argument type for gameId.", "{integer}", "'$gameId'" ) );
    }

    public function renderGameIdDoesNotExist( $command, $gameId )
    {
        return( $this->renderGenericErrorMessage( $command, "Invalid domain.", "{valid_integer} in the range of demo/Games/Game*.php.", "$gameId" ) );
    }

    private function renderGenericErrorMessage( $command, $errorMessage, $expected, $actual )
    {
        $page = $this->widgets->header();
        $page .= $this->widgets->error( $errorMessage, $expected, $actual );
        $page .= $this->widgets->usage( $command );

        return $page;
    }
}
