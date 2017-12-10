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
if (!function_exists("copyDir")) {
    function copyDir($form, $to)
    {
        if (is_dir($form)) {
            if (!file_exists($to)) {
                mkdir($to);
            }
            if ($handler = opendir($form)) {
                while (($file = readdir($handler)) !== false) {
                    if ($file != "." && $file != "..") {
                        $toFile = $to . $file;
                        if (!file_exists($toFile)) {
                            @copy($form . $file, $toFile);
                        }
                    }
                }
            }
        }
    }
}