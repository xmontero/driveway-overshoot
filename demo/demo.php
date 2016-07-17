<?php

namespace XaviMontero\DrivewayOvershoot\Demo;

//-------------------------------------------------------------------------//
// Set the autoloader up                                                   //
//-------------------------------------------------------------------------//

error_reporting( E_ALL ^ E_NOTICE ^ E_USER_WARNING );
require_once( __DIR__ . '/../vendor/autoload.php' );

//-------------------------------------------------------------------------//
// Create the services to inject into the controller                       //
//-------------------------------------------------------------------------//

// CLI parser service.
$commandLineParser = new Helpers\CommandLineParser( $argv );

// Sudoku Loader service.
$gridLoader = new Helpers\GridLoaderFileImplementation();

// View Renderers services.
$ansi = new Helpers\AnsiColorCodesGenerator();
$widgets = new Views\AnsiWidgets( $ansi );
$successViewRenderer = new Views\SuccessView( $widgets );
$errorViewRenderer = new Views\ErrorView( $widgets );
$viewRenderers = [ 'success' => $successViewRenderer, 'error' => $errorViewRenderer ];

//-------------------------------------------------------------------------//
// Create the controller injecting services, and run it.                   //
//-------------------------------------------------------------------------//

$controller = new Controllers\DefaultController( $commandLineParser, $gridLoader, $viewRenderers );
$controller->run();
