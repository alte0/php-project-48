<?php

namespace Parsers;

use Symfony\Component\Yaml\Yaml;

/** Парсим данные из файла
 * @param string $filePath
 * @return array
 */
function getData(string $filePath = ''): array
{
    $realPathFile = \realpath($filePath);

    if (!\is_file($realPathFile)) {
        return [];
    }

    $extFile = \pathinfo($realPathFile, PATHINFO_EXTENSION);
    $allowExtDiff = ['json', 'yaml', 'yml'];

    if (!\in_array($extFile, $allowExtDiff)) {
        return [];
    }

    $contentFile = \file_get_contents($realPathFile);

    if ('json' === $extFile) {
        return \json_decode($contentFile, true);
    } elseif (in_array($extFile, ['yaml', 'yml'])) {
        $value = Yaml::parse($contentFile, Yaml::PARSE_OBJECT_FOR_MAP);

        return (array)$value;
    }
}
