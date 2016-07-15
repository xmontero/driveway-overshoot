<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Candidates;
use XaviMontero\DrivewayOvershoot\CandidatesState;
use XaviMontero\DrivewayOvershoot\OneToNineValue;

class CandidatesTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
        $this->sut = new Candidates();
    }

    //---------------------------------------------------------------------//
    // Tests                                                               //
    //---------------------------------------------------------------------//

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Candidates', $this->getSut() );
    }

    //-- States -----------------------------------------------------------//

    public function testCreationLeavesAFullState()
    {
        $state = $this->getSut()->getState();
        $this->assertEquals( CandidatesState::Full(), $state );
    }

    /**
     * @dataProvider killingSomeLeavesASemiStateProvider
     */
    public function testKillingSomeLeavesASemiState( $kill )
    {
        $this->killSutByArray( $kill );
        $state = $this->getSut()->getState();
        $this->assertEquals( CandidatesState::Semi(), $state );
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
        $this->assertEquals( CandidatesState::Single(), $state );
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
        $this->assertEquals( CandidatesState::Empty(), $state );
    }

    //-- Content ----------------------------------------------------------//

    /**
     * @dataProvider killingEliminatesTheOptionsProvider
     */
    public function testKillingEliminatesTheOptions( $kill )
    {
        foreach( $kill as $killValue )
        {
            $this->getSut()->killOption( new OneToNineValue( $killValue ) );
        }

        for( $i = 1; $i <= 9; $i++ )
        {
            $expected = ! in_array( $i, $kill );
            $actual = $this->getSut()->isOption( new OneToNineValue( $i ) );

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
        $this->assertTrue( $this->getSut()->getSingleOption()->equals( new OneToNineValue( $expected ) ) );
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

    //-- Resetting --------------------------------------------------------//

    public function testReset()
    {
        $sut = $this->getSut();

        $this->killSutByArray( [ 4, 7 ] );
        $this->assertEquals( CandidatesState::Semi(), $sut->getState() );

        $sut->reset();
        $this->assertEquals( CandidatesState::Full(), $sut->getState() );
    }

    //---------------------------------------------------------------------//
    // Private                                                             //
    //---------------------------------------------------------------------//

    private function getSut() : Candidates
    {
        return $this->sut;
    }

    private function killSutByArray( $kill )
    {
        foreach( $kill as $killValue )
        {
            $this->getSut()->killOption( new OneToNineValue( $killValue ) );
        }
    }
}
