<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

/**
 * Value object that represents an integer ranging from 1 to 9.
 *
 * It is identified by its value, which can be any integer from 1 to 9.
 * Useful for representing the possible values for a Cell, the id of a row, of a column, of a box, the position of a Cell inside a row, of a column, or inside a box, etc.
 */
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

    /**
     * The value, backconverted to a standard integer type.
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }

    /**
     * True if the other value equals this one.
     * @param OneToNineValue $other - The value to cbe compared to.
     * @return bool
     */
    public function equals( OneToNineValue $other )
    {
        return ( $this->getValue() === $other->getValue() );
    }

    //-- Private ----------------------------------------------------------//

    private function assertInRange( int $value )
    {
        if( ( $value < self::Min ) || ( $value > self::Max ) )
        {
            throw new \DomainException( "Range must be between " . self::Min . ' and ' . self::Max . '. Got value ' . $value . ' at creation.' );
        }
    }
}
