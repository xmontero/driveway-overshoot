<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

class ValueTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new Value( 3 );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Value', $sut );
    }

    /**
     * @dataProvider creationThrowsDomainExceptionIfValueIsOutOfRangeProvider
     */
    public function testCreationThrowsDomainExceptionIfValueIsOutOfRange( int $value )
    {
        $this->expectException( \DomainException::class );
        $sut = new Value( $value );
    }

    public function creationThrowsDomainExceptionIfValueIsOutOfRangeProvider()
    {
        return
        [
            [ 0 ],
            [ -1 ],
            [ -1000 ],
            [ 10 ],
            [ 11 ],
            [ 1000 ],
        ];
    }

    /**
     * @dataProvider creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider
     */
    public function testCreationThrowsTypeExceptionIfValueIsOfWrongType( $value )
    {
        $this->expectException( \TypeError::class );
        $sut = new Value( $value );
    }

    public function creationThrowsTypeExceptionIfValueIsOfWrongTypeProvider()
    {
        return
            [
                [ 'hello' ],
                [ true ],
                [ null ],
            ];
    }

    /**
     * @dataProvider getValueReturnsCreationValueProvider
     */
    public function testGetValueReturnsCreationValue( int $value )
    {
        $sut = new Value( $value );
        $this->assertEquals( $value, $sut->getValue() );
    }

    public function getValueReturnsCreationValueProvider()
    {
        return
        [
            [ 1 ],
            [ 2 ],
            [ 5 ],
            [ 8 ],
            [ 9 ],
        ];
    }
}
