<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\CandidatesState;

class CandidatesStateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new CandidatesState( CandidatesState::Full );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\CandidatesState', $sut );
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue( $value )
    {
        $sut = new CandidatesState( $value );
        $this->assertEquals( $value, $sut->getValue() );
    }

    public function getValueProvider()
    {
        return
        [
            [ CandidatesState::Full ],
            [ CandidatesState::Semi ],
            [ CandidatesState::Single ],
            [ CandidatesState::Empty ],
        ];
    }
}
