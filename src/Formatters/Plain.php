<?php

namespace Formatters\Plain;

use function Help\valueToString;

/** Стиль показа сравнения - plain
 * @param mixed $data
 * @param string $keyName
 * @return string
 */
function plainFormatter($data, string $keyName = ''): string
{
    $arrStr = array_map(
        function ($item) use ($keyName) {
            $keyName = empty($keyName) ? $item['key'] : $keyName . '.' . $item['key'];

            $str = '';

            if ($item['type'] === 'deleted') {
                $str = "Property '{$keyName}' was removed";
            } elseif ($item['type'] === 'nested') {
                $str = plainFormatter($item['value'], $keyName);
            } elseif ($item['type'] === 'changed') {
                $str = "Property '{$keyName}' was updated. From " . valToString($item['value']) .
                    ' to ' . valToString($item['value2']);
            } elseif ($item['type'] === 'add') {
                $str = "Property '{$keyName}' was added with value: " . valToString($item['value']);
            }

            return $str;
        },
        $data
    );

    $arrWithoutEmptyStr = array_filter($arrStr);

    $str = implode(PHP_EOL, $arrWithoutEmptyStr);

    return $str;
}

function valToString($val): string
{
    if (is_array($val)) {
        $res = '[complex value]';
    } else {
        $res = valueToString($val, false);
    }

    return $res;
}
