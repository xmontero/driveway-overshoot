<?php

namespace DrivewayOvershoot\Demo\Views;

class ErrorView
{
    private $widgets;

    public function __construct( $widgets )
    {
        $this->widgets = $widgets;
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
        $page = $this->widgets->header();
        $page .= $this->widgets->body( $command, $errorMessage );

        return $page;
    }
}
