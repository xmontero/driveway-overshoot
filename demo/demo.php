<?php

namespace DrivewayOvershoot\Demo;

//-------------------------------------------------------------------------//
// Set the autoloader up                                                   //
//-------------------------------------------------------------------------//

require_once( 'ClassAutoloader.php' );

$autoloader = new ClassAutoloader();
$autoloader->setupAutoloader();

//-------------------------------------------------------------------------//
// Create the services to inject into the controller                       //
//-------------------------------------------------------------------------//

$commandLineParser = new Helpers\CommandLineParser( $argv );

$sudokuReader = new Helpers\SudokuReaderFileImplementation();

$ansi = new Helpers\AnsiColorCodesGenerator();
$successViewRenderer = new Views\SuccessView( $ansi );
$errorViewRenderer = new Views\ErrorView( $ansi );
$viewRenderers = [ 'success' => $successViewRenderer, 'error' => $errorViewRenderer ];

//-------------------------------------------------------------------------//
// Create the controller injecting services, and run it.                   //
//-------------------------------------------------------------------------//

$controller = new Controllers\DefaultController( $commandLineParser, $sudokuReader, $viewRenderers );
$controller->run();
