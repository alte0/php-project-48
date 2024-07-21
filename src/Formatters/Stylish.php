<?php

namespace Formatters\Stylish;

use function Help\valueToString;

/** Стиль показа сравнения - stylish
 * @param mixed $data
 * @param int $depth
 * @return string
 */
function stylishFormatter($data, int $depth = 1): string
{
    if (!is_array($data)) {
        return valueToString($data);
    }

    $isHasNotTypeKeys = empty(array_column($data, 'type'));

    if ($isHasNotTypeKeys) {
        $arrDiffStr = getDiffByWithoutTypeKey($data, $depth);
    } else {
        $arrDiffStr = getDiffByTypeKey($data, $depth);
    }

    $arrStart = ['{'];
    $arrEnd = [calcCountCharsRepeat($depth - 1) . '}'];
    $str = implode(PHP_EOL, array_merge($arrStart, $arrDiffStr, $arrEnd));

    return $str;
}

/**
 * @param mixed $data
 * @param int $depth
 * @return array
 */
function getDiffByTypeKey($data, int $depth): array
{
    $arrDiffStr = array_reduce(
        $data,
        function ($acc, $item) use ($depth) {
            $nextDepth = $depth + 1;

            if ($item['type'] === 'deleted') {
                $key = calcCountCharsRepeat($depth, 2) . '- ' . $item['key'] . ': ';
                $acc[] = $key . stylishFormatter($item['value'], $nextDepth);
            } elseif ($item['type'] === 'unchanged' || $item['type'] === 'nested') {
                $key = calcCountCharsRepeat($depth) . $item['key'] . ': ';
                $acc[] = $key . stylishFormatter($item['value'], $nextDepth);
            } elseif ($item['type'] === 'changed') {
                $key1 = calcCountCharsRepeat($depth, 2) . '- ' . $item['key'] . ': ';
                $acc[] = $key1 . stylishFormatter($item['value'], $nextDepth);
                $key2 = calcCountCharsRepeat($depth, 2) . '+ ' . $item['key'] . ': ';
                $acc[] = $key2 . stylishFormatter($item['value2'], $nextDepth);
            } elseif ($item['type'] === 'add') {
                $key = calcCountCharsRepeat($depth, 2) . '+ ' . $item['key'] . ': ';
                $acc[] = $key . stylishFormatter($item['value'], $nextDepth);
            }

            return $acc;
        },
        []
    );

    return $arrDiffStr;
}

/**
 * @param mixed $data
 * @param int $depth
 * @return array
 */
function getDiffByWithoutTypeKey($data, int $depth): array
{
    $keys = array_keys($data);

    $arrDiffStr = array_map(
        function ($key) use ($data, $depth) {
            $res = calcCountCharsRepeat($depth) . $key . ': ' . stylishFormatter($data[$key], $depth + 1);

            return $res;
        },
        $keys
    );

    return $arrDiffStr;
}

/** Вычисляет количество отступов для ключа массива
 * @param int $depth
 * @param int $leftOffset
 * @return string
 */
function calcCountCharsRepeat(int $depth, int $leftOffset = 0): string
{
    $spaceCount = 4;
    $replacer = ' ';

    return str_repeat($replacer, $depth * $spaceCount - $leftOffset);
}
