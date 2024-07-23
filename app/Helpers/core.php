<?php

/**
 * @param string|null $key
 * @param $default
 * @return mixed
 */
function settings(string $key = null, $default = null): mixed
{
    if ($key != null) {
        return config('settings.' . $key, $default);
    }

    return config('settings');
}

if (!function_exists('formatDate')) {
    /**
     * @param $date
     * @param $format
     * @return mixed|string
     */
    function formatDate($date, $format = 'Y-m-d')
    {
        if ($date) {
            if ($date instanceof DateTime) {
                $date = $date->format($format);
            } else {
                $date = date($format, strtotime($date));
            }
        }
        return $date;
    }
}
/**
 * @param $string
 * @return bool
 */
function isJson($string)
{
    if (!$string) {
        return false;
    }
    return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
}

function array_filter_recursive(array $array, callable $callback = null)
{
    $array = is_callable($callback) ? array_filter($array, $callback) : array_filter($array);
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            $value = call_user_func(__FUNCTION__, $value, $callback);

            if (empty($value)) {
                unset($array[$key]);
            }
        }
    }

    return $array;
}

function mask_string(string $string, $visible = null)
{
    $length = strlen($string);

    $visibleCount = $visible ?: (int)round($length / 4);
    $hiddenCount = $length - ($visibleCount * 2);

    if ($length <= ($visible * 2)) {
        return $string;
    }

    $prefix = substr($string, 0, $visibleCount);
    $suffix = substr($string, ($visibleCount * -1), $visibleCount);
    $mask = str_repeat('*', $hiddenCount);

    return $prefix . $mask . $suffix;
}
