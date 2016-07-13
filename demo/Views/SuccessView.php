<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Views;

use XaviMontero\DrivewayOvershoot\SudokuGrid;

class SuccessView
{
    private $widgets;

    public function __construct( AnsiWidgets $widgets )
    {
        $this->widgets = $widgets;
    }

    public function render( SudokuGrid $sudoku ) : string
    {
        $page = $this->widgets->header();

        $filesContent = "        * The demo.php is the FRONT-CONTROLLER that sets the autoloader up, and then calls the real controller.
        * The Controllers/DefaultController.php is the CONTROLLER in the MVC pattern.
        * The Views/SuccessView.php is the VIEW in the MVC pattern.
        * The the project itself (under src/) is the MODEL in the MVC pattern." . PHP_EOL;
        $page .= $this->widgets->block( 'FILES', $filesContent );

        $howItWorksContent = "        The CONTROLLER
            a) gets access to the MODEL,
            b) operates it (ie: controls it),
            c) gets its resulting data,
            d) transforms the data into a DTO suitable for the VIEW, and
            e) renders the view." . PHP_EOL;
        $page .= $this->widgets->block( 'HOW IT WORKS', $howItWorksContent );

        $page .= $this->widgets->block( 'PROBLEM', $this->widgets->sudoku( 'problem', $sudoku ) );

        $page .= $this->widgets->block( 'SOLUTION', $this->widgets->sudoku( 'solution', $sudoku ) );

        return $page;
    }
}
