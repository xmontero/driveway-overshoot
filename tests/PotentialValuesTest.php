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
}
