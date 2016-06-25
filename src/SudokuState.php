<?php

namespace XaviMontero\DrivewayOvershoot;

use MyCLabs\Enum\Enum;

class SudokuState extends Enum
{
    const Editable = 0;
    const Resolved = 1;
}
