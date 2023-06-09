<?php

namespace App\Traits\Models;

use ReflectionClass;

trait ConstantsGetter
{
    public static function getConstants($group = null)
    {
        $instance = new ReflectionClass(__CLASS__);

        $classConstants = $instance->getConstants();

        if (is_null($group)) {
            return $classConstants;
        }

        $groupedConstants = [];
        foreach ($classConstants as $key => $value) {
            if (strpos($key, $group) !== false) {
                $groupedConstants[$key] = $value;
            }
        }

        return $groupedConstants;
    }
}
