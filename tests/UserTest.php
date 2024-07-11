<?php
namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use function \GenDiff\genDiff;
use function \Parsers\getData;

class UserTest extends TestCase
{
    /** Получение тестовых файлов
     * @param string $fileName
     * @return string
     */
    protected function getFixture(string $fileName = ''): string
    {
        return __DIR__ . '/fixtures/' . $fileName;
    }

    public function testGenDiffEmptyPath(): void
    {
        $arrExpected = [
            '{',
            '}'
        ];
        $expected = implode(PHP_EOL, $arrExpected);
        $filePath1 = $this->getFixture();
        $filePath2 = $this->getFixture();
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2FilesJson(): void
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
        $filePath1 = $this->getFixture('file1.json');
        $filePath2 = $this->getFixture('file2.json');
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2FilesYaml(): void
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
        $filePath1 = $this->getFixture('file1.yaml');
        $filePath2 = $this->getFixture('file2.yaml');
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2FilesYml(): void
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
        $filePath1 = $this->getFixture('file1.yml');
        $filePath2 = $this->getFixture('file2.yml');
        $actual = genDiff($filePath1, $filePath2);
        $this->assertEquals($expected, $actual);
    }
}