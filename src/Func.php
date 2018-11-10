<?php

namespace App;

class Func {
    public static function allKeysToCamelCase(array $input) {
        $return = array();
        foreach ($input as $key => $value) {
            $key = \Jasny\camelcase($key);

            if (is_array($value))
                $value = self::allKeysToCamelCase($value);

            $return[$key] = $value;
        }
        return $return;
    }
}
