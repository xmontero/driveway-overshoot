<?php

namespace XaviMontero\DrivewayOvershoot;

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
    }

    public function testKillingSomeLeavesASemiState()
    {
        $this->getSut()->killOption( new Value( 3 ) );
        $state = $this->getSut()->getState();
        $this->assertEquals( PotentialValuesState::Semi(), $state );
    }
}
