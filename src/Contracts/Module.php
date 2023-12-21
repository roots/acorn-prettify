<?php

namespace Roots\AcornPretty\Contracts;

interface Module
{
    /**
     * Handle the module.
     *
     * @return array
     */
    public function handle();
}
