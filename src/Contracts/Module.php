<?php

namespace Roots\AcornPretty\Contracts;

interface Module
{
    /**
     * Handle the module.
     */
    public function handle(): void;
}
