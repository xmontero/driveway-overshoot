<?php

namespace XaviMontero\DrivewayOvershoot\Tests;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\SudokuFactory;

class SudokuFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationIsOfProperClass()
    {
        $persister = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sut = new SudokuFactory( $persister );

        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\SudokuFactory', $sut );
    }

    public function testCreateSudokuReturnsProperType()
    {
        $persister = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sut = new SudokuFactory( $persister );

        $sudoku = $sut->createSudoku();
        $this->assertInstanceOf( 'XaviMontero\\DrivewayOvershoot\\Sudoku', $sudoku );
    }

    public function testCreationCallsTheReader()
    {
        $persister = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sut = new SudokuFactory( $persister );

        $sut->createSudoku();
        $this->assertEquals( 81, $persister->callCountHasClue );
        $this->assertEquals( 36, $persister->callCountGetClue );
    }

    public function testCreationDoesPredefinedResult()
    {
        $coordinates = new Coordinates( new OneToNineValue( 7 ), new OneToNineValue( 3 ) );

        $persister = new Helpers\SudokuLoaderInMemoryImplementation( 'empty' );
        $sut = new SudokuFactory( $persister );

        $sudoku = $sut->createSudoku();

        $this->assertFalse( $sudoku->getCell( $coordinates )->hasValue() );

        $persister = new Helpers\SudokuLoaderInMemoryImplementation( 'easy1' );
        $sut = new SudokuFactory( $persister );

        $sudoku = $sut->createSudoku();

        $this->assertTrue( $sudoku->getCell( $coordinates )->hasValue() );
        $this->assertEquals( 4, $sudoku->getCell( $coordinates )->getValue()->getValue() );
    }
}
