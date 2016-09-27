<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Games;

class Game1 implements GameInterface
{
    // Compatible determinate (AKA proper puzzle).
    function getClues() : array
    {
        return
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
    }
}
