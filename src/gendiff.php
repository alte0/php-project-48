<?php

namespace GenDiff;

use Docopt;

function initApp(): void
{
    $result = initDocoptGenDiff();
    $args = $result->args;
    runGenDiff($args);
}

function initDocoptGenDiff(): Docopt\Response
{

    $doc = <<<DOC
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help         Show this screen.
      -v --version      Show version.
      --format <fmt>    Report format [default: stylish]

    DOC;

    return \Docopt::handle($doc, ['version' => 'Generate diff 0.1']);
}

function runGenDiff(array $arg): bool
{
    if (isset($arg['<firstFile>']) && isset($arg['<secondFile>'])) {
        echo genDiff($arg['<firstFile>'], $arg['<secondFile>']) . PHP_EOL;

        return true;
    }

    return false;
}

function genDiff(string $pathFile1, string $pathFile2): string
{
    $resDiff = [];
    $resDiff[] = '{';

    $realPathFile1 = \realpath($pathFile1);
    $realPathFile2 = \realpath($pathFile2);

    if (is_file($realPathFile1) && is_file($realPathFile2)) {
        $contentFile1 = \file_get_contents($realPathFile1);
        $contentFile2 = \file_get_contents($realPathFile2);

        $arrFile1 = \json_decode($contentFile1, true);
        $arrFile2 = \json_decode($contentFile2, true);

        \ksort($arrFile1, SORT_STRING);
        \ksort($arrFile2, SORT_STRING);

        do {
            $continue = false;
            $key1 = \key($arrFile1);
            $key2 = \key($arrFile2);

            if ($key1 !== null && $key2 !== null) {
                $value1 = getValueString($arrFile1[$key1]);
                $value2 = getValueString($arrFile2[$key2]);

                if ($key1 === $key2) {
                    if ($value1 === $value2) {
                        $resDiff[] = "  $key1: $value1";
                    } else {
                        $resDiff[] = "- $key1: $value1";
                        $resDiff[] = "+ $key2: $value2";
                    }

                    \next($arrFile1);
                    \next($arrFile2);
                    $continue = true;
                } elseif (!\array_key_exists($key1, $arrFile2)) {
                    $resDiff[] = "- $key1: $value1";

                    \next($arrFile1);
                    $continue = true;
                }
            } elseif ($key2 !== null && $key1 === null) {
                $keyLast2 = \array_key_last($arrFile2);
                $value = getValueString($arrFile2[$keyLast2]);

                $resDiff[] = "+ $keyLast2: $value";

                \next($arrFile2);
                $continue = true;
            }
        } while ($continue);
    }

    $resDiff[] = '}';

    return \implode(PHP_EOL, $resDiff);
}

/**
 * @param string|int|bool $value
 * @return string
 */
function getValueString($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return (string)$value;
}
