<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;
use function \GenDiff\genDiff;
use function Formatter\setFormatter;

class DifferTest extends TestCase
{
    private string $format = '';
    private string $expectedEmpty = '';
    private string $expectedRec = '';
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
    }

    public function testGenDiffEmptyPath(): void
    {
        $actual = genDiff('', '', $this->format);
        $this->assertEquals($this->expectedEmpty, $actual);
    }

    public function testGenDiff2FilesJsonFinal(): void
    {
            $filePath1 = $this->getFixture('fileFinal1.json');
            $filePath2 = $this->getFixture('fileFinal2.json');
            $actual = genDiff($filePath1, $filePath2, $this->format);

            $this->assertEquals($this->expectedRec, $actual);
    }
}