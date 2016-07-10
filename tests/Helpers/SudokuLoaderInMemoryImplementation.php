<?php

namespace XaviMontero\DrivewayOvershoot\Tests\Helpers;

use XaviMontero\DrivewayOvershoot\Coordinates;
use XaviMontero\DrivewayOvershoot\OneToNineValue;
use XaviMontero\DrivewayOvershoot\Sudoku;
use XaviMontero\DrivewayOvershoot\SudokuLoaderInterface;

class SudokuLoaderInMemoryImplementation implements SudokuLoaderInterface
{
    public $callCountLoad = 0;
    public $callCountSave = 0;

    public function load( string $gameId, Sudoku $sudoku )
    {
        $this->callCountLoad++;

        switch( $gameId )
        {
            case 'empty':

                $values =
                    [
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                    ];

                break;

            case 'incompatibleCluesInRow':

                $values =
                    [
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   2, 0, 0,   0, 0, 0 ],

                        [ 1, 0, 0,   0, 0, 0,   0, 0, 1 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 3,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                    ];

                break;

            case 'incompatibleCluesInColumn':

                $values =
                    [
                        [ 0, 0, 0,   1, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 3,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 3,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 2 ],
                    ];

                break;

            case 'incompatibleCluesInBox':

                $values =
                    [
                        [ 0, 0, 0,   1, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 1,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 2, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],

                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 3 ],
                    ];

                break;

            case 'incompatibleCluesHard':

                $values =
                    [
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 0, 0, 0,   0, 8, 0,   0, 0, 5 ],
                        [ 0, 7, 3,   0, 0, 0,   8, 5, 0 ],

                        [ 0, 6, 0,   0, 0, 0,   0, 0, 0 ],
                        [ 1, 0, 0,   0, 1, 0,   0, 9, 0 ],
                        [ 0, 0, 6,   0, 0, 0,   0, 0, 4 ],

                        [ 0, 0, 7,   0, 0, 9,   0, 0, 4 ],
                        [ 0, 0, 3,   0, 2, 0,   0, 2, 0 ],
                        [ 0, 0, 0,   0, 0, 0,   0, 0, 0 ],
                    ];

                break;

            case 'easy1':

                $values =
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

                break;

            case 'solved':

                $values =
                    [
                        [ 2, 8, 6,   9, 4, 5,   1, 7, 3 ],
                        [ 7, 1, 4,   6, 3, 2,   9, 5, 8 ],
                        [ 9, 3, 5,   7, 8, 1,   4, 2, 6 ],

                        [ 4, 2, 7,   3, 5, 6,   8, 1, 9 ],
                        [ 6, 5, 8,   1, 9, 7,   3, 4, 2 ],
                        [ 1, 9, 3,   4, 2, 8,   7, 6, 5 ],

                        [ 3, 6, 1,   5, 7, 9,   2, 8, 4 ],
                        [ 5, 4, 2,   8, 1, 3,   6, 9, 7 ],
                        [ 8, 7, 9,   2, 6, 4,   5, 3, 1 ],
                    ];

                break;

            case 'nearlySolved':

                $values =
                    [
                        [ 2, 8, 6,   9, 4, 5,   1, 7, 3 ],
                        [ 7, 1, 4,   6, 3, 2,   9, 5, 8 ],
                        [ 9, 3, 5,   7, 8, 1,   4, 2, 6 ],

                        [ 4, 2, 7,   3, 5, 6,   8, 1, 9 ],
                        [ 6, 5, 8,   1, 9, 7,   3, 4, 2 ],
                        [ 1, 9, 3,   4, 2, 8,   7, 6, 5 ],

                        [ 3, 6, 1,   5, 7, 9,   2, 8, 4 ],
                        [ 5, 4, 2,   8, 1, 3,   6, 9, 7 ],
                        [ 8, 7, 9,   2, 6, 4,   5, 3, 0 ],
                    ];

                break;

            default:

                throw new \DomainException( "Game not found '" . $gameId . "'" );
                break;
        }

        for( $y = 1; $y <= 9; $y++ )
        {
            for( $x = 1; $x <= 9; $x++ )
            {
                $value = $values[ $y - 1 ][ $x - 1 ];

                if( $value != 0 )
                {
                    $coordinates = new Coordinates( new OneToNineValue( $x ), new OneToNineValue( $y ) );
                    $sudoku->getCell( $coordinates )->setClue( new OneToNineValue( $value ) );
                }
            }
        }
    }
}
