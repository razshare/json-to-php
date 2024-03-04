<?php
namespace App;

function stringSnakeToPascal(string $value) {
    return ucfirst(stringSnakeToCamel($value));
}

function stringSnakeToCamel(string $value) {
    $result    = '';
    $upperNext = false;
    foreach (str_split($value) as $char) {
        if ('_' === $char) {
            $upperNext = true;
            continue;
        }

        if ($upperNext) {
            $result .= strtoupper($char);
            $upperNext = false;
        } else {
            $result .= $char;
        }
    }
    return $result;
}