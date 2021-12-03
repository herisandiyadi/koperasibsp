<?php
namespace App\Helpers;

use JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter;
use JeroenNoten\LaravelAdminLte\Menu\Builder;


class BSPMenuFilter extends GateFilter
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['role']) && !auth()->user()->hasAnyRole($item['role'])) {
            return false;
        }
        if (! $this->isVisible($item)) {
            return false;
        }
        return $item;
    }
}