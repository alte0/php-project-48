<?php
namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use function \GenDiff\genDiff;

class UserTest extends TestCase
{
    public function testGenDiffEmptyPath(): void
    {
        $arrExpected = [
            '{',
            '}'
        ];
        $expected = implode(PHP_EOL, $arrExpected);
        $filePath1 = '';
        $filePath2 = '';
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }
    public function testGenDiff2Files(): void
    {
        $arrExpected = [
            '{',
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true',
            '}'
        ];
        $expected = implode(PHP_EOL, $arrExpected);
        $filePath1 = __DIR__ . '/fixtures/file1.json';
        $filePath2 = __DIR__ . '/fixtures/file2.json';
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }
}