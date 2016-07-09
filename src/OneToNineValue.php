<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

class OneToNineValue
{
    const Min = 1;
    const Max = 9;
    private $value;

    public function __construct( int $value )
    {
        $this->assertInRange( $value );

        $this->value = $value;
    }

    public function getValue() : int
    {
        return $this->value;
    }

    private function assertInRange( int $value )
    {
        if( ( $value < self::Min ) || ( $value > self::Max ) )
        {
            throw new \DomainException( "Range must be between " . self::Min . ' and ' . self::Max . '. Got value ' . $value . ' at creation.' );
        }
    }
}
