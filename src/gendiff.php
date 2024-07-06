<?php

namespace GenDiff;

use Docopt;

function gendiff()
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

    $args = Docopt::handle($doc, ['version' => 'Generate diff 0.1']);
    foreach ($args as $k => $v)
        echo $k . ': ' . json_encode($v) . PHP_EOL;
}
