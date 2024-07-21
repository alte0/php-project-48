<?php

namespace Differ;

use Docopt;

use function Help\isAssoc;
use function Parsers\parseData;
use function Formatters\setFormatter;
use function Formatters\allowFormat;
use function Formatters\getDiffByFormat;

function initApp(): void
{
    $result = initDocopt();
    $args = $result->args;

    runGenDiff($args);
}

function initDocopt(): Docopt\Response
{
    $formatReports = implode(', ', allowFormat());
    $doc = <<<DOC
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help         Show this screen.
      -v --version      Show version.
      --format <fmt>    Report format [default: $formatReports]

    DOC;

    return \Docopt::handle($doc, ['version' => 'Generate diff 0.1']);
}

function runGenDiff(array $args): bool
{
    if (isset($args['<firstFile>']) && isset($args['<secondFile>'])) {
        $format = !isset($args['--format']) ? setFormatter() : $args['--format'];

        echo genDiff($args['<firstFile>'], $args['<secondFile>'], setFormatter($format)) . PHP_EOL;

        return true;
    }

    return false;
}

function genDiff(string $pathFile1, string $pathFile2, string $format): string
{
    $arrFile1 = parseData($pathFile1);
    $arrFile2 = parseData($pathFile2);

    $format = setFormatter($format);

    $diff = diff($arrFile1, $arrFile2);

    $str = getDiffByFormat($diff, $format);

    return $str;
}

function diff(array $arrFile1, array $arrFile2): array
{
    $keys = array_merge(array_keys($arrFile1), array_keys($arrFile2));
    $keysUnique = array_unique($keys);
    sort($keysUnique, SORT_STRING);

    $diff = array_map(
        function ($key) use ($arrFile1, $arrFile2) {
            if (!array_key_exists($key, $arrFile2)) {
                return ['key' => $key, 'type' => 'deleted', 'value' => $arrFile1[$key]];
            }

            if (!array_key_exists($key, $arrFile1)) {
                return ['key' => $key, 'type' => 'add', 'value' => $arrFile2[$key]];
            }

            if ($arrFile1[$key] === $arrFile2[$key]) {
                return ['key' => $key, 'type' => 'unchanged', 'value' => $arrFile1[$key]];
            }

            if (
                is_array($arrFile1[$key]) && is_array($arrFile2[$key]) &&
                isAssoc($arrFile1[$key]) && isAssoc($arrFile2[$key])
            ) {
                return ['key' => $key, 'type' => 'nested', 'value' => diff($arrFile1[$key], $arrFile2[$key])];
            }

            return ['key' => $key, 'type' => 'changed', 'value' => $arrFile1[$key], 'value2' => $arrFile2[$key]];
        },
        $keysUnique
    );

    return $diff;
}
