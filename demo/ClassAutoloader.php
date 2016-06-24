<?php

namespace XaviMontero\DrivewayOvershoot\Demo;

class ClassAutoloader
{
    public function setupAutoloader()
    {
        spl_autoload_register( array( $this, 'loader' ) );
    }

    private function loader( $className )
    {
        $prefix = 'XaviMontero\\DrivewayOvershoot\\Demo\\';
        $prefixLen = strlen( $prefix );
        if( substr( $className, 0, $prefixLen ) === $prefix )
        {
            $trailingClassName = substr( $className, $prefixLen );

            $filename = __DIR__ . '/' . str_replace( '\\', '/', $trailingClassName ) . '.php';
            if( file_exists( $filename ) )
            {
                include( $filename );
            }
            else
            {
                trigger_error( 'Invalid class name ' . $className . ' within the ' . $prefix . ' namespace.', E_USER_WARNING );
            }
        }
        else
        {
            trigger_error( 'Invalid class name ' . $className . '.', E_USER_WARNING );
        }
    }
}
