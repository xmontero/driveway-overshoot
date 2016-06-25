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
        if( ! $this->loadNamespaceFromPath( $className, 'XaviMontero\\DrivewayOvershoot\\Demo\\', '/' ) )
        {
            if( ! $this->loadNamespaceFromPath( $className, 'XaviMontero\\DrivewayOvershoot\\', '/../src/' ) )
            {
                trigger_error( 'Invalid class name ' . $className . '.', E_USER_WARNING );
            }
        }
    }

    private function loadNamespaceFromPath( $className, $prefix, $path ) : bool
    {
        $prefixLen = strlen( $prefix );
        $foundNameSpace = ( substr( $className, 0, $prefixLen ) === $prefix );

        if( $foundNameSpace )
        {
            $trailingClassName = substr( $className, $prefixLen );

            $filename = __DIR__ . $path . str_replace( '\\', '/', $trailingClassName ) . '.php';
            if( file_exists( $filename ) )
            {
                include( $filename );
            }
            else
            {
                trigger_error( 'Invalid class name ' . $className . ' within the ' . $prefix . ' namespace.', E_USER_WARNING );
            }
        }

        return $foundNameSpace;
    }
}
