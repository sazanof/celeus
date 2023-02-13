<?php

/**
 * Get env value
 * @param $key
 * @param $default
 * @return mixed
 */
function env($key, $default = null): mixed
{
    if(!empty($_ENV) && isset($_ENV[$key])){
        return $_ENV[$key];
    } else {
        return $default;
    }
}

function mb_ucfirst($text) {
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

function wrap($value)
{
    if (is_null($value)) {
        return [];
    }

    return is_array($value) ? $value : [$value];
}