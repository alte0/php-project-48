<?php

namespace Formatters;

use function Formatters\Stylish\stylishFormatter;
use function Formatters\Plain\plainFormatter;
use function Formatters\Json\jsonFormatter;

/** Получение формата отчёта сравнения
 * @param array $arr
 * @param string $format
 * @return string
 */
function getDiffByFormat(array $arr, string $format): string
{
    $res = '';

    switch ($format) {
        case 'json':
            $res = jsonFormatter($arr);
            break;
        case 'plain':
            $res = plainFormatter($arr);
            break;
        case 'stylish':
            $res = stylishFormatter($arr);
            break;
    }

    return $res;
}

/** Установка формата/проверка, что устанавливаемый формат из списка возможных форматов
 * @param string $format
 * @return string
 */
function setFormatter(string $format = 'stylish'): string
{
    $defaultFormat = 'stylish';

    if (in_array($format, allowFormat())) {
        return $format;
    }

    return $defaultFormat;
}

/** Список разрешённых форматов
 * @return string[]
 */
function allowFormat(): array
{
    return ['stylish', 'plain', 'json'];
}
