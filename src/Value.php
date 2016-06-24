<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

class Value
{
    const Min = 1;
    const Max = 9;

    private $value;

    public function __construct( int $value )
    {
        if( ( $value < self::Min ) || ( $value > self::Max ) )
        {
            throw new \DomainException( 'Range must be between ' . self::Min . ' and ' . self::Max . '.' );
        }

        $this->value = $value;
    }

    public function getValue() : int
    {
        return $this->value;
    }

    public function equals( Value $other )
    {
        return ( $this->getValue() === $other->getValue() );
    }
}
