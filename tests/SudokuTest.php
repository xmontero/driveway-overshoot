<?php

namespace XaviMontero\DrivewayOvershoot;

class SudokuTest extends \PHPUnit_Framework_TestCase
{
    public function testIsOfProperClass()
    {
        $sut = new Sudoku();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Sudoku', $sut );
    }
}
