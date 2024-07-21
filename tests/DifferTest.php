<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use function \GenDiff\genDiff;
use function Formatters\setFormatter;

class DifferTest extends TestCase
{
    private string $format = '';
    private string $expectedEmpty = '';
    private string $expectedRec = '';
    private string $expectedPlain = '';
    private string $path = __DIR__ . "/fixtures/";

    /** Получение тестовых файлов
     * @param string $fileName
     * @return string
     */
    protected function getFixture(string $fileName = ''): string
    {
        return $this->path . $fileName;
    }

    protected function setUp(): void
    {
        $this->format = setFormatter();

        $arrExpectedEmpty = [
            '{',
            '}'
        ];
        $this->expectedEmpty = implode(PHP_EOL, $arrExpectedEmpty);

        $arrExpectedRec = [
            '{',
            '    common: {',
            '      + follow: false',
            '        setting1: Value 1',
            '      - setting2: 200',
            '      - setting3: true',
            '      + setting3: null',
            '      + setting4: blah blah',
            '      + setting5: {',
            '            key5: value5',
            '        }',
            '        setting6: {',
            '            doge: {',
            '              - wow: ',
            '              + wow: so much',
            '            }',
            '            key: value',
            '          + ops: vops',
            '        }',
            '    }',
            '    group1: {',
            '      - baz: bas',
            '      + baz: bars',
            '        foo: bar',
            '      - nest: {',
            '            key: value',
            '        }',
            '      + nest: str',
            '    }',
            '  - group2: {',
            '        abc: 12345',
            '        deep: {',
            '            id: 45',
            '        }',
            '    }',
            '  + group3: {',
            '        deep: {',
            '            id: {',
            '                number: 45',
            '            }',
            '        }',
            '        fee: 100500',
            '    }',
            '}'
        ];
        $this->expectedRec = implode(PHP_EOL, $arrExpectedRec);

        $arrExpectedPlain = [
            "Property 'common.follow' was added with value: false",
            "Property 'common.setting2' was removed",
            "Property 'common.setting3' was updated. From true to null",
            "Property 'common.setting4' was added with value: 'blah blah'",
            "Property 'common.setting5' was added with value: [complex value]",
            "Property 'common.setting6.doge.wow' was updated. From '' to 'so much'",
            "Property 'common.setting6.ops' was added with value: 'vops'",
            "Property 'group1.baz' was updated. From 'bas' to 'bars'",
            "Property 'group1.nest' was updated. From [complex value] to 'str'",
            "Property 'group2' was removed",
            "Property 'group3' was added with value: [complex value]",
        ];
        $this->expectedPlain = implode(PHP_EOL, $arrExpectedPlain);
    }

    public function testGenDiffEmptyPath(): void
    {
        $actual = genDiff('', '', $this->format);
        $this->assertEquals($this->expectedEmpty, $actual);
    }

    public function testGenDiff2FilesJson(): void
    {
            $filePath1 = $this->getFixture('fileFinal1.json');
            $filePath2 = $this->getFixture('fileFinal2.json');
            $actual = genDiff($filePath1, $filePath2, $this->format);

            $this->assertEquals($this->expectedRec, $actual);
    }

    public function testGenDiff2FilesPlain(): void
    {
        $filePath1 = $this->getFixture('fileFinal1.json');
        $filePath2 = $this->getFixture('fileFinal2.json');
        $actual = genDiff($filePath1, $filePath2, setFormatter('plain'));

        $this->assertEquals($this->expectedPlain, $actual);
    }
}