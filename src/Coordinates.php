<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

class Coordinates
{
    const Min = 1;
    const Max = 9;

    private $x;
    private $y;

    public function __construct( int $x, int $y )
    {
        $this->assertInRange( 'x', $x );
        $this->assertInRange( 'y', $y );

        $this->x = $x;
        $this->y = $y;
    }

    public function getX() : int
    {
        return $this->x;
    }

    public function getY() : int
    {
        return $this->y;
    }

    private function assertInRange( string $coordinateName, int $value )
    {
        if( ( $value < self::Min ) || ( $value > self::Max ) )
        {
            throw new \DomainException( "Range of '" . $coordinateName . "' must be between " . self::Min . ' and ' . self::Max . '.' );
        }
    }
}
