<?php

namespace XaviMontero\DrivewayOvershoot;

interface SudokuObserverInterface
{
    public function onEditableChanged( bool $newEditable );
}
