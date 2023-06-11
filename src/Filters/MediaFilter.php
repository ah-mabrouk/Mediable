<?php

namespace Mabrouk\Mediable\Filters;

use Mabrouk\Filterable\Helpers\QueryFilter;

class MediaFilter extends QueryFilter
{
    public function type($type = '')
    {
        return $type != '' ? $this->builder->ofType($type) : $this->builder;
    }

    public function title($title = '')
    {
        return $title != '' ? $this->builder->withTitle($title) : $this->builder;
    }

    public function main($main = 'yes')
    {
        return \in_array($main, \array_keys($this->availableBooleanValues)) ? $this->builder->main($this->availableBooleanValues[$main]) : $this->builder;
    }
}
