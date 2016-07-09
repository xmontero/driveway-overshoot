<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\OneToNineValue;

class OneToNineValueTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new OneToNineValue( 3 );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\OneToNineValue', $sut );
    }

    /**
     * @dataProvider creationThrowsDomainExceptionIfValueIsOutOfRangeProvider
     */
    public function testCreationThrowsDomainExceptionIfValueIsOutOfRange( int $value )
    {
        $this->expectException( \DomainException::class );
        new OneToNineValue( $value );
    }

    public function creationThrowsDomainExceptionIfValueIsOutOfRangeProvider()
    {
        return
            [
                [ 0 ],
                [ -1 ],
                [ -1000 ],
                [ 10 ],
                [ 100 ],
            ];
    }

    /**
     * @dataProvider creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider
     */
    public function testCreationThrowsTypeExceptionIfValueIsOfWrongType( $value )
    {
        $this->expectException( \TypeError::class );
        new OneToNineValue( $value );
    }

    public function creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider()
    {
        return
            [
                [ 'hello' ],
                [ true, ],
                [ null, ],
            ];
    }

    /**
     * @dataProvider getValueReturnsCreationValueProvider
     */
    public function testGetValueReturnsCreationValue( int $value )
    {
        $sut = new OneToNineValue( $value );
        $this->assertEquals( $value, $sut->getValue() );
    }

    public function getValueReturnsCreationValueProvider()
    {
        return
            [
                [ 1 ],
                [ 2 ],
                [ 5 ],
                [ 9 ],
            ];
    }
}
