<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\SudokuState;

class SudokuStateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new SudokuState( SudokuState::Editable );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuState', $sut );
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue( $value )
    {
        $sut = new SudokuState( $value );
        $this->assertEquals( $value, $sut->getValue() );
    }

    public function getValueProvider()
    {
        return
            [
                [ SudokuState::Editable ],
                [ SudokuState::Resolved ],
            ];
    }
}
