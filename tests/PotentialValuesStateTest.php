<?php

namespace XaviMontero\DrivewayOvershoot;

class PotentialValuesStateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new PotentialValuesState( PotentialValuesState::Full );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\PotentialValuesState', $sut );
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue( $value )
    {
        $sut = new PotentialValuesState( $value );
        $this->assertEquals( $value, $sut->getValue() );
    }

    public function getValueProvider()
    {
        return
        [
            [ PotentialValuesState::Full ],
            [ PotentialValuesState::Semi ],
            [ PotentialValuesState::Single ],
            [ PotentialValuesState::Empty ],
        ];
    }
}
