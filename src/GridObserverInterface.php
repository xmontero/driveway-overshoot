<?php

namespace XaviMontero\DrivewayOvershoot;

/**
 * Interface that allows to listen to the changes in the Sudoku object.
 */
interface GridObserverInterface
{
    public function onEditableChanged( bool $newEditable );
}
