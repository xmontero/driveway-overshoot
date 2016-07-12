<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Controllers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Demo\Helpers\CommandLineParser;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuFactory;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;

class DefaultController
{
    private $commandLineParser;
    private $sudokuLoader;
    private $viewRenderers;
    private $command;
    private $gameId;

    public function __construct( CommandLineParser $commandLineParser, SudokuLoaderInterface $sudokuLoader, array $viewRenderers )
    {
        $this->commandLineParser = $commandLineParser;
        $this->sudokuLoader = $sudokuLoader;
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
        $this->gameId = $this->commandLineParser->getGameId();
        if( ! ctype_digit( $this->gameId ) )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderArgumentIsNotInteger( $this->command, $this->gameId );
            throw new \Exception( $errorMessage );
        }
    }

    public function assertGameIdExists()
    {
        $gameExists = $this->sudokuLoader->gameExists( $this->gameId );
        if( ! $gameExists )
        {
            $errorMessage = $this->viewRenderers[ 'error' ]->renderGameIdDoesNotExist( $this->command, $this->gameId );
            throw new \Exception( $errorMessage );
        }
    }

    private function cleanRun()
    {
        $sudoku = $this->getSudokuModel();
        $sudoku = $this->operateSudokuModel( $sudoku );
        $view = $this->renderTheViewWithSudoku( $sudoku );

        $this->printPage( $view );
    }

    private function getSudokuModel() : Sudoku
    {
        $this->sudokuLoader->loadClues( $this->gameId );
        $sudokuFactory = new SudokuFactory( $this->sudokuLoader );
        return $sudokuFactory->createSudoku();
    }

    private function operateSudokuModel( Sudoku $sudoku ) : Sudoku
    {
        $sudoku->getCell( new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 1 ) ) )->setSolutionValue( new OneToNineValue( 5 ) );
        return $sudoku;
    }

    private function renderTheViewWithSudoku( Sudoku $sudoku ) : string
    {
        return $this->viewRenderers[ 'success' ]->render( $sudoku );
    }

    private function printPage( string $page )
    {
        echo( $page );
    }
}
