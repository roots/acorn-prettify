<?php

namespace Roots\AcornPrettify\Concerns;

use Illuminate\Support\Collection;

trait HasCollection
{
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return Collection
     */
    protected function collect($value = [])
    {
        return Collection::make($value);
    }
}
