<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\PotentialValues;
use XaviMontero\DrivewayOvershoot\PotentialValuesState;
use XaviMontero\DrivewayOvershoot\Value;

class PotentialValuesTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new PotentialValues();
    }

    //---------------------------------------------------------------------//
    // Tests                                                               //
    //---------------------------------------------------------------------//

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\PotentialValues', $this->getSut() );
    }

    //-- States -----------------------------------------------------------//

    public function testCreationLeavesAFullState()
    {
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Full(), $state );
    }

    /**
     * @dataProvider killingSomeLeavesASemiStateProvider
     */
    public function testKillingSomeLeavesASemiState( $kill )
    {
        $this->killSutByArray( $kill );
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Semi(), $state );
    }

    public function killingSomeLeavesASemiStateProvider()
    {
        return
        [
            [ [ 3 ] ],
            [ [ 3, 5 ] ],
            [ [ 4, 5, 1, 8 ] ],
        ];
    }

    /**
     * @dataProvider killingEightValuesLeavesASingleProvider
     */
    public function testKillingEightValuesLeavesASingleState( $kill )
    {
        $this->killSutByArray( $kill );
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Single(), $state );
    }

    public function killingEightValuesLeavesASingleProvider()
    {
        return
        [
            [ [ 2, 3, 4, 5, 6, 7, 8, 9 ] ],
            [ [ 1, 2, 4, 5, 6, 7, 8, 9 ] ],
            [ [ 1, 2, 3, 4, 6, 7, 8, 9 ] ],
            [ [ 1, 2, 3, 4, 5, 6, 8, 9 ] ],
            [ [ 1, 2, 3, 4, 5, 6, 7, 8 ] ],
        ];
    }

    public function testKillingAllValuesLeavesASingleState()
    {
        $this->killSutByArray( [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ] );
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Empty(), $state );
    }

    //-- Content ----------------------------------------------------------//

    /**
     * @dataProvider killingEliminatesTheOptionsProvider
     */
    public function testKillingEliminatesTheOptions( $kill )
    {
        foreach( $kill as $killValue )
        {
            $this->getSut()->killOption( new Value( $killValue ) );
        }

        for( $i = 1; $i <= 9; $i++ )
        {
            $expected = ! in_array( $i, $kill );
            $actual = $this->getSut()->isOption( new Value( $i ) );

            $this->assertEquals( $expected, $actual );
        }
    }

    public function killingEliminatesTheOptionsProvider()
    {
        return
        [
            [ [ ] ],
            [ [ 1 ] ],
            [ [ 3 ] ],
            [ [ 3, 5 ] ],
            [ [ 8, 9 ] ],
            [ [ 4, 5, 1, 8 ] ],
            [ [ 2, 3, 4, 5, 6, 7, 8, 9 ] ],
            [ [ 1, 2, 3, 4, 5, 7, 8, 9 ] ],
            [ [ 1, 2, 3, 4, 5, 6, 7, 8 ] ],
            [ [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ] ],
        ];
    }

    /**
     * @dataProvider getSingleOptionThrowsExceptionIfInImproperValueProvider
     */
    public function testGetSingleOptionThrowsExceptionIfInImproperValue( $kill )
    {
        $this->killSutByArray( $kill );

        $this->expectException( \LogicException::class );
        $this->getSut()->getSingleOption();
    }

    public function getSingleOptionThrowsExceptionIfInImproperValueProvider()
    {
        return
            [
                [ [ ] ],
                [ [ 3 ] ],
                [ [ 8, 9 ] ],
                [ [ 4, 5, 1, 8 ] ],
                [ [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ] ],
            ];
    }

    /**
     * @dataProvider getSingleOptionReturnsNonKilledValue
     */
    public function testGetSingleOptionReturnsNonKilledValue( $kill, $expected )
    {
        $this->killSutByArray( $kill );
        $this->assertTrue( $this->getSut()->getSingleOption()->equals( new Value( $expected ) ) );
    }

    public function getSingleOptionReturnsNonKilledValue()
    {
        return
            [
                [ [ 2, 3, 4, 5, 6, 7, 8, 9 ], 1 ],
                [ [ 1, 2, 3, 4, 5, 7, 8, 9 ], 6 ],
                [ [ 1, 2, 3, 4, 5, 6, 7, 8 ], 9 ],
            ];
    }

    //---------------------------------------------------------------------//
    // Private                                                             //
    //---------------------------------------------------------------------//

    private function getSut() : PotentialValues
    {
        return $this->sut;
    }

    private function killSutByArray( $kill )
    {
        foreach( $kill as $killValue )
        {
            $this->getSut()->killOption( new Value( $killValue ) );
        }
    }
}
