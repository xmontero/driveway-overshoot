<?php

namespace XaviMontero\DrivewayOvershoot\Tests\Helpers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;
use XaviMontero\DrivewayOvershoot\SudokuSaverInterface;
use XaviMontero\DrivewayOvershoot\Value;

class SudokuPersisterInMemoryImplementation implements SudokuLoaderInterface, SudokuSaverInterface
{
    public $callCountLoad = 0;
    public $callCountSave = 0;

    public function load( string $gameId, Sudoku $sudoku )
    {
        $this->callCountLoad++;

        switch( $gameId )
        {
            case 'empty':

                break;

            case 'easy1':

                $easy1 =
                    [
                        [ 0, 0, 3,   9, 0, 0,   0, 5, 1 ],
                        [ 5, 4, 6,   0, 1, 8,   3, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 7,   4, 2, 0 ],

                        [ 0, 0, 9,   0, 5, 0,   0, 3, 0 ],
                        [ 2, 0, 0,   6, 0, 3,   0, 0, 4 ],
                        [ 0, 8, 0,   0, 7, 0,   2, 0, 0 ],

                        [ 0, 9, 7,   3, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 1,   8, 2, 0,   9, 4, 7 ],
                        [ 8, 5, 0,   0, 0, 4,   6, 0, 0 ],
                    ];

                for( $y = 1; $y <= 9; $y++ )
                {
                    for( $x = 1; $x <= 9; $x++ )
                    {
                        $value = $easy1[ $y - 1 ][ $x - 1 ];

                        if( $value != 0 )
                        {
                            $coordinates = new Coordinates( $x, $y );
                            $sudoku->getTile( $coordinates )->setInitialValue( new Value( $value ) );
                        }
                    }
                }
                break;

            default:

                throw new \DomainException( "Game not found '" . $gameId . "'" );
                break;
        }
    }

    public function save( string $gameId, Sudoku $sudoku )
    {
        //$this->callCountSave++;
    }
}
