<?php

namespace DrivewayOvershoot\Demo;

class ClassAutoloader
{
    public function setupAutoloader()
    {
        spl_autoload_register( array( $this, 'loader' ) );
    }

    private function loader( $className )
    {
        $prefix = 'DrivewayOvershoot\\Demo\\';
        $prefixLen = strlen( $prefix );
        if( substr( $className, 0, $prefixLen ) === $prefix )
        {
            $trailingClassName = substr( $className, $prefixLen );
            include( __DIR__ . '/' . str_replace( '\\', '/', $trailingClassName ) . '.php' );
        }
        else
        {
            throw new \Exception( 'Invalid class name.' );
        }
    }
}
