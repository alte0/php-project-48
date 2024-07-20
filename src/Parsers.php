<?php

namespace Parsers;

use Symfony\Component\Yaml\Yaml;

use function Help\getExtFile;

/** Парсим данные из файла
 * @param string $filePath
 * @return array
 */
function parseData(string $filePath): array
{
    $realPathFile = \realpath($filePath);

    if (!\is_file($realPathFile)) {
        return [];
    }

    $extFile = getExtFile($realPathFile);

    if (!isAllowParseFile($extFile)) {
        return [];
    }

    $contentsFile = \file_get_contents($realPathFile);

    if (mb_strlen($contentsFile) === false) {
        return [];
    }

    return parseContent($contentsFile, $extFile);
}

/** Список разрешенных файлов для парсинга данных
 * @param string $extFile
 * @return bool
 */
function isAllowParseFile(string $extFile): bool
{
    $allowExtDiff = ['json', 'yaml', 'yml'];

    return \in_array($extFile, $allowExtDiff, true);
}

/** Парсинг данных полученных из файла
 * @param $contentFile
 * @param string $extFile
 * @return array
 */
function parseContent($contentFile, string $extFile): array
{
    if ('json' === $extFile) {
        return \json_decode($contentFile, true);
    } elseif (in_array($extFile, ['yaml', 'yml'])) {
        $value = Yaml::parse($contentFile, Yaml::PARSE_OBJECT_FOR_MAP);

        return (array)$value;
    }

    return [];
}
