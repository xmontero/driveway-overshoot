<?php

namespace DrivewayOvershoot\Demo\Controllers;

class DefaultController
{
    private $commandLineParser;
    private $sudokuReader;
    private $viewRenderers;
    private $command;

    public function __construct( $commandLineParser, $sudokuReader, $viewRenderers )
    {
        $this->commandLineParser = $commandLineParser;
        $this->sudokuReader = $sudokuReader;
        $this->viewRenderers = $viewRenderers;

        $this->command = $commandLineParser->getcommand();
    }

    public function run()
    {
        try
        {
            $this->assertNumberOfCliArguments();
            $this->assertGameIdIsInteger();
            $this->assertGameIdExists();

            $this->cleanRun();
        }
        catch( \Exception $e )
        {
            $this->printPage( $e->getMessage() );
        }
    }

    public function assertNumberOfCliArguments()
    {
        $numberOfCliArguments = $this->commandLineParser->getNumberOfArguments();
        if( $numberOfCliArguments != 1 )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderInvalidNumberOfArguments( $this->command, $numberOfCliArguments );
            throw new \Exception( $errorMessage );
        }
    }

    public function assertGameIdIsInteger()
    {
        $gameId = $this->commandLineParser->getGameId();
        if( ! ctype_digit( $gameId ) )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderArgumentIsNotInteger( $this->command, $gameId );
            throw new \Exception( $errorMessage );
        }
    }

    public function assertGameIdExists()
    {
        // TODO: Must check that the game exists.
    }

    private function cleanRun()
    {
        /*
a) gets access to the MODEL,
b) operates it (ie: controls it),
c) gets its resulting data,
d) transforms the data into a DTO suitable for the VIEW, and
e) renders the view.
        */

        $this->printPage( $this->renderTheView() );
    }

    private function renderTheView()
    {
        $viewData = new \stdClass();
        $viewData->name = 'world';

        return $this->viewRenderers[ 'success' ]->render( $viewData );
    }

    private function printPage( $page )
    {
        echo( $page );
    }
}
