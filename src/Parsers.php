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
    $realPathFile = (string)\realpath($filePath);

    if (!\is_file($realPathFile)) {
        return [];
    }

    $extFile = getExtFile($realPathFile);

    if (!isAllowParseFile($extFile)) {
        return [];
    }

    $contents = \file_get_contents($realPathFile);
    $contentsFile = $contents !== false ? $contents : '';

    if (mb_strlen($contentsFile) === 0) {
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
 * @param string $contentFile
 * @param string $extFile
 * @return array
 */
function parseContent(string $contentFile, string $extFile): array
{
    if ('json' === $extFile) {
        return \json_decode($contentFile, true);
    } elseif (in_array($extFile, ['yaml', 'yml'], true)) {
        return Yaml::parse($contentFile);
    }

    return [];
}
