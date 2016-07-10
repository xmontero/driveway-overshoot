<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $sut = new Coordinates( new OneToNineValue( 3 ), new OneToNineValue( 3 ) );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Coordinates', $sut );
    }

    public function testGetValueReturnsProperClass()
    {
        $sut = new Coordinates( new OneToNineValue( 2 ), new OneToNineValue( 9 ) );

        $rowId = $sut->getRowId();
        $columnId = $sut->getColumnId();
        $squareId = $sut->getSquareId();

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\OneToNineValue', $rowId );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\OneToNineValue', $columnId );
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\OneToNineValue', $squareId );
    }

    /**
     * @dataProvider getRowAndColumnReturnCreationValueProvider
     */
    public function testGetRowAndColumnReturnCreationValue( int $columnIdValue, int $rowIdValue )
    {
        $sut = new Coordinates( new OneToNineValue( $columnIdValue ), new OneToNineValue( $rowIdValue ) );

        $rowId = $sut->getRowId();
        $columnId = $sut->getColumnId();

        $this->assertEquals( $rowIdValue, $rowId->getValue() );
        $this->assertEquals( $columnIdValue, $columnId->getValue() );
    }

    public function getRowAndColumnReturnCreationValueProvider()
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

    /**
     * @dataProvider getSquareReturnsProperValueProvider
     */
    public function testGetSquareReturnsProperValue( int $columnIdValue, int $rowIdValue, int $expectedSquareIdValue )
    {
        $sut = new Coordinates( new OneToNineValue( $columnIdValue ), new OneToNineValue( $rowIdValue ) );
        $squareId = $sut->getSquareId();

        $this->assertEquals( $expectedSquareIdValue, $squareId->getValue() );
    }

    public function getSquareReturnsProperValueProvider()
    {
        return
            [
                [ 1, 1, 1 ],
                [ 3, 1, 1 ],
                [ 4, 1, 2 ],
                [ 6, 1, 2 ],
                [ 7, 1, 3 ],
                [ 9, 1, 3 ],

                [ 1, 3, 1 ],
                [ 3, 3, 1 ],
                [ 4, 3, 2 ],
                [ 6, 3, 2 ],
                [ 7, 3, 3 ],
                [ 9, 3, 3 ],

                [ 1, 4, 4 ],
                [ 3, 4, 4 ],
                [ 4, 4, 5 ],
                [ 6, 4, 5 ],
                [ 7, 4, 6 ],
                [ 9, 4, 6 ],

                [ 1, 6, 4 ],
                [ 3, 6, 4 ],
                [ 4, 6, 5 ],
                [ 6, 6, 5 ],
                [ 7, 6, 6 ],
                [ 9, 6, 6 ],

                [ 1, 7, 7 ],
                [ 3, 7, 7 ],
                [ 4, 7, 8 ],
                [ 6, 7, 8 ],
                [ 7, 7, 9 ],
                [ 9, 7, 9 ],

                [ 1, 9, 7 ],
                [ 3, 9, 7 ],
                [ 4, 9, 8 ],
                [ 6, 9, 8 ],
                [ 7, 9, 9 ],
                [ 9, 9, 9 ],
            ];
    }
}
