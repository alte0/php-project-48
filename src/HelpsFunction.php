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
 * @param mixed $value
 * @param bool $removeSingleQuotes
 * @return string
 */
function valueToString($value, bool $removeSingleQuotes = true): string
{
    $res = var_export($value, true);
    $lowerValue = $res === 'NULL' ? strtolower($res) : $res;
    $str = $removeSingleQuotes ? trim($lowerValue, "'") : $lowerValue;

    return $str;
}

function getExtFile(string $pathFile): string
{
    return \pathinfo($pathFile, PATHINFO_EXTENSION);
}
