<?php

namespace Formatter\Stylish;

use function Help\valueToString;

/** Стиль показа сравнения - stylish
 * @param array $arr
 * @return string
 */
function stylishFormatter(array $arr): string
{
    $stylishFormatter = function ($data, int $depth = 1) use (&$stylishFormatter) {
        if (!is_array($data)) {
            return valueToString($data);
        }

        $isHasNotTypeKeys = empty(array_column($data, 'type'));

        if ($isHasNotTypeKeys) {
            $keys = array_keys($data);

            $arrDiffStr = array_map(
                function ($key) use ($data, $depth, &$stylishFormatter) {
                    $res = calcCountCharsRepeat($depth) . $key . ': ' . $stylishFormatter($data[$key], $depth + 1);

                    return $res;
                },
                $keys
            );
        } else {
            $arrDiffStr = array_reduce(
                $data,
                function ($acc, $item) use ($depth, &$stylishFormatter) {
                    $nextDepth = $depth + 1;

                    if ($item['type'] === 'deleted') {
                        $key = calcCountCharsRepeat($depth, 2) . '- ' . $item['key'] . ': ';
                        $acc[] = $key . $stylishFormatter($item['value'], $nextDepth);
                    } elseif ($item['type'] === 'unchanged' || $item['type'] === 'nested') {
                        $key = calcCountCharsRepeat($depth) . $item['key'] . ': ';
                        $acc[] = $key . $stylishFormatter($item['value'], $nextDepth);
                    } elseif ($item['type'] === 'changed') {
                        $key1 = calcCountCharsRepeat($depth, 2) . '- ' . $item['key'] . ': ';
                        $acc[] = $key1 . $stylishFormatter($item['value'], $nextDepth);
                        $key2 = calcCountCharsRepeat($depth, 2) . '+ ' . $item['key'] . ': ';
                        $acc[] = $key2 . $stylishFormatter($item['value2'], $nextDepth);
                    } elseif ($item['type'] === 'add') {
                        $key = calcCountCharsRepeat($depth, 2) . '+ ' . $item['key'] . ': ';
                        $acc[] = $key . $stylishFormatter($item['value'], $nextDepth);
                    }

                    return $acc;
                },
                []
            );
        }

        $arrStart = ['{'];
        $arrEnd = [calcCountCharsRepeat($depth - 1) . '}'];
        $str = implode(PHP_EOL, array_merge($arrStart, $arrDiffStr, $arrEnd));

        return $str;
    };

    return $stylishFormatter($arr);
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
