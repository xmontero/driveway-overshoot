<?php

namespace XaviMontero\DrivewayOvershoot;

use MyCLabs\Enum\Enum;

class PotentialValuesState extends Enum
{
    const Empty = 0;
    const Single = 1;
    const Semi = 2;
    const Full = 9;
}
