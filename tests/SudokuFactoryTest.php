<?php

namespace XaviMontero\DrivewayOvershoot;

class SudokuFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
        $persister = new Tests\Helpers\SudokuPersisterInMemoryImplementation();
        $this->sut = new SudokuFactory( $persister );
    }

    private function getSut() : SudokuFactory
    {
        return $this->sut;
    }

    public function testCreationIsOfProperClass()
    {
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuFactory', $this->getSut() );
    }
}
