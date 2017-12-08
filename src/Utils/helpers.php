<?php
if (!function_exists("tap")) {
    function tap($value, $callback)
    {
        $callback($value);
        return $value;
    }
}
if (!function_exists("value")) {
    function value($default)
    {
        return $default instanceof Closure ? $default() : $default;
    }
}