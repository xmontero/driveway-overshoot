<?php

declare( strict_types = 1 );

namespace XaviMontero\DrivewayOvershoot;

class Coordinates
{
    private $column;
    private $row;

    public function __construct( OneToNineValue $column, OneToNineValue $row )
    {
        $this->column = $column;
        $this->row = $row;
    }

    public function getColumn() : OneToNineValue
    {
        return $this->column;
    }

    public function getRow() : OneToNineValue
    {
        return $this->row;
    }

    public function getSquare() : OneToNineValue
    {
        $x = $this->getColumn()->getValue();
        $y = $this->getRow()->getValue();

        $squareX = intdiv( $x - 1, 3 ) + 1;
        $squareY = intdiv( $y - 1, 3 );

        $squareId = $squareX + $squareY * 3;

        return new OneToNineValue( $squareId );
    }

    private function assertInRange( string $coordinateName, int $value )
    {
        if( ( $value < self::Min ) || ( $value > self::Max ) )
        {
            throw new \DomainException( "Range of '" . $coordinateName . "' must be between " . self::Min . ' and ' . self::Max . '.' );
        }
    }
}
