<?php

namespace XaviMontero\DrivewayOvershoot\Demo\Helpers;

class AnsiColorCodesGenerator
{
    public function reset()
    {
        return "\033[0m";
    }

    public function red()
    {
        return "\033[1;31m";
    }

    public function green()
    {
        return "\033[1;32m";
    }

    public function yellow()
    {
        return "\033[1;33m";
    }

    public function blue()
    {
        return "\033[1;34m";
    }

    public function darkBlue()
    {
        return "\033[34m";
    }
}
