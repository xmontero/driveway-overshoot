<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Controllers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Demo\Helpers\CommandLineParser;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Grid;
use XaviMontero\DrivewayOvershoot\SudokuFactory;
use XaviMontero\DrivewayOvershoot\GridLoaderInterface;
use XaviMontero\DrivewayOvershoot\Solver;

class DefaultController
{
    private $commandLineParser;
    private $gridLoader;
    private $viewRenderers;
    private $command;
    private $gameId;

    public function __construct( CommandLineParser $commandLineParser, GridLoaderInterface $gridLoader, array $viewRenderers )
    {
        $this->commandLineParser = $commandLineParser;
        $this->gridLoader = $gridLoader;
        $this->viewRenderers = $viewRenderers;

        $this->command = $commandLineParser->getcommand();
    }

    //-- Public -----------------------------------------------------------//

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

    //-- Private ----------------------------------------------------------//

    private function assertNumberOfCliArguments()
    {
        $numberOfCliArguments = $this->commandLineParser->getNumberOfArguments();
        if( $numberOfCliArguments != 1 )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderInvalidNumberOfArguments( $this->command, $numberOfCliArguments );
            throw new \Exception( $errorMessage );
        }
    }

    private function assertGameIdIsInteger()
    {
        $this->gameId = $this->commandLineParser->getGameId();
        if( ! ctype_digit( $this->gameId ) )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderArgumentIsNotInteger( $this->command, $this->gameId );
            throw new \Exception( $errorMessage );
        }
    }

    private function assertGameIdExists()
    {
        $gameExists = $this->gridLoader->gameExists( $this->gameId );
        if( ! $gameExists )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderGameIdDoesNotExist( $this->command, $this->gameId );
            throw new \Exception( $errorMessage );
        }
    }

    private function cleanRun()
    {
        $grid = $this->getSudokuModel();
        $grid = $this->operateSudokuModel( $grid );
        $view = $this->renderTheViewWithSudoku( $grid );

        $this->printPage( $view );
    }

    private function getSudokuModel() : Grid
    {
        $this->gridLoader->loadClues( $this->gameId );
        $sudokuFactory = new SudokuFactory( $this->gridLoader );
        return $sudokuFactory->createSudoku();
    }

    private function operateSudokuModel( Grid $grid ) : Grid
    {
        $solver = new Solver( $grid );
        $solver->solve();
        return $grid;
    }

    private function renderTheViewWithSudoku( Grid $grid ) : string
    {
        return $this->viewRenderers[ 'success' ]->render( $grid );
    }

    private function printPage( string $page )
    {
        echo( $page );
    }
}
