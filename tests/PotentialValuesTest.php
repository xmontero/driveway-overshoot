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

    private function getSut() : PotentialValues
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\PotentialValues', $this->getSut() );
    }

    public function testCreationLeavesAFullState()
    {
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Full(), $state );
    }

    public function testCreationHasAllNumbers()
    {
        for( $i = 1; $i <= 9; $i++ )
        {
            $this->assertTrue( $this->getSut()->isOption( new Value( $i ) ) );
        }

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
     * @dataProvider killing8ValuesLeavesASingleProvider
     */
    public function testKilling8ValuesLeavesASingleState( $kill )
    {
        $this->killSutByArray( $kill );
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Single(), $state );
    }

    public function killing8ValuesLeavesASingleProvider()
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

    private function killSutByArray( $kill )
    {
        foreach( $kill as $killValue )
        {
            $this->getSut()->killOption( new Value( $killValue ) );
        }
    }
}
