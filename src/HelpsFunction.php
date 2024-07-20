<?php

namespace Help;

/** Проверка на ассоциативный массив
 * @param array $arr
 * @return bool
 */
function isAssoc(array $arr): bool
{
    return \array_keys($arr) !== \range(0, count($arr) - 1);
}

/** Преобразовывает значение к строке
 * @param $value
 * @return string
 */
function valueToString($value): string
{
    $str = var_export($value, true);

    if ($value === null) {
        $str = strtolower($str);
    }

    return $str !== null ? trim($str, "'") : '';
}

function getExtFile(string $pathFile)
{
    return \pathinfo($pathFile, PATHINFO_EXTENSION);
}
