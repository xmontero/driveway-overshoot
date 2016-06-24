<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new Coordinates( 3, 3 );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Coordinates', $sut );
    }

    /**
     * @dataProvider creationThrowsDomainExceptionIfValueIsOutOfRangeProvider
     */
    public function testCreationThrowsDomainExceptionIfValueIsOutOfRange( int $x, int $y )
    {
        $this->expectException( \DomainException::class );
        new Coordinates( $x, $y );
    }

    public function creationThrowsDomainExceptionIfValueIsOutOfRangeProvider()
    {
        return
            [
                [ 0, 3 ],
                [ -1, -4 ],
                [ 7, -1000 ],
                [ 9, 10 ],
                [ 10, 100 ],
                [ 11, 7 ],
                [ 1000, 0 ],
            ];
    }

    /**
     * @dataProvider creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider
     */
    public function testCreationThrowsTypeExceptionIfValueIsOfWrongType( $x, $y )
    {
        $this->expectException( \TypeError::class );
        new Coordinates( $x, $y );
    }

    public function creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider()
    {
        return
            [
                [ 'hello', 4 ],
                [ true, 7 ],
                [ null, 8 ],
                [ 1, 'hello' ],
                [ 2, true ],
                [ 8, null ],
            ];
    }

    /**
     * @dataProvider getValueReturnsCreationValueProvider
     */
    public function testGetValueReturnsCreationValue( int $x, int $y )
    {
        $sut = new Coordinates( $x, $y );
        $this->assertEquals( $x, $sut->getX() );
        $this->assertEquals( $y, $sut->getY() );
    }

    public function getValueReturnsCreationValueProvider()
    {
        return
            [
                [ 1, 1 ],
                [ 1, 4 ],
                [ 1, 9 ],
                [ 2, 4 ],
                [ 5, 1 ],
                [ 8, 9 ],
                [ 9, 1 ],
                [ 9, 6 ],
                [ 9, 9 ],
            ];
    }
}
