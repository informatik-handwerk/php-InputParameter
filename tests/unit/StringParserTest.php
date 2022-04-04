<?php

namespace ihde\php\InputParameter\codeception;

use DateTimeInterface;
use ihde\php\InputParameter\StringParser;

class StringParserTest
    extends \Codeception\Test\Unit {
    /**
     * @var \ihde\php\InputParameter\codeception\UnitTester
     */
    protected $tester;
    
    protected function _before(): void {
    }
    
    protected function _after(): void {
    }
    
    /**
     * @return array
     */
    public function provideListWithLength(): array {
        return [
            "list, deformed" => [StringParser::SPLITTER_list, 0],
            "list, deformed end" => ["a" . StringParser::SPLITTER_list, 1],
            "list, deformed start" => [StringParser::SPLITTER_list . "b", 1],
            "list, deformed succession" => ["a" . StringParser::SPLITTER_list . StringParser::SPLITTER_list . "c", 2],
            "list, classical" => ["a" . StringParser::SPLITTER_list . "b", 2],
            "list, with range" => ["a..A" . StringParser::SPLITTER_list . " b", 2],
            "list, normal long" => ["a" . StringParser::SPLITTER_list . " b" . StringParser::SPLITTER_list . " c", 3],
        ];
    }
    
    /**
     * @return array
     */
    public function provideRange(): array {
        return [
            "range, deformed left" => [StringParser::SPLITTER_range . "c", [null, "c"]],
            "range, deformed right" => ["a" . StringParser::SPLITTER_range, ["a", null]],
            "range, normal" => ["a" . StringParser::SPLITTER_range . "c", ["a", "c"]],
        ];
    }
    
    /**
     * @return array
     */
    public function provideInvalidRange(): array {
        $spitter = StringParser::SPLITTER_range;
        $splitterLeadin = \mb_substr($spitter, 0, 1);
        $splitterLeadout = \mb_substr($spitter, -1);
        
        return [
            "invalid range, both sides empty" => [$spitter],
            "invalid range, too much leadin" => ["a" . $splitterLeadin . $spitter . "b"],
            "invalid range, too much leadout" => ["a" . $spitter . $splitterLeadout . "b"],
            "invalid range, concatenation" => ["a" . $spitter . $spitter . "b"],
        ];
    }
    
    /**
     * @return array
     */
    public function provideStandalone(): array {
        return [
            "standalone, void" => [""],
            "standalone, value" => ["v"],
        ];
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function provideIntegerTestables(): array {
        $phpIntMaxAsString = (string)PHP_INT_MAX;
        $true = [
            "integer, zero" => "0",
            "integer, one" => "1",
            "integer, random" => (string)\random_int(0, PHP_INT_MAX),
            "integer, maxInt" => $phpIntMaxAsString,
        ];
        
        $false = [
            "not integer, char" => "a",
            "not integer, prefixed" => "0a",
            "not integer, suffixed" => "a0",
            "not integer, binary" => "0b1",
            "not integer, octal" => "01",
            "not integer, hex" => "0x1",
            "not integer, negative" => "-1",
            "not integer, number after maxInt" => (string)(PHP_INT_MAX + 1),
            "not integer, maxInt string successor" => $phpIntMaxAsString++,
            "not integer, maxInt prefixed" => "1" . PHP_INT_MAX,
        ];
        
        $result = \array_merge(
            \array_map(static fn($s) => [$s, true], $true),
            \array_map(static fn($s) => [$s, false], $false),
        );
        
        return $result;
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function provideDateTestables(): array {
        $true = [
            "date, now" => "now",
            "date, timestamp native" => "@0",
            "date, timestamp pure int" => "0",
            "date, date only" => "2022-01-01",
            "date, date with time" => "2022-01-01 01:01.01",
            //tests do not pass dates with timezones
            //DateTimeInterface::W3C => (new \DateTime())->format(DateTimeInterface::W3C),
            //DateTimeInterface::RFC3339_EXTENDED => (new \DateTime())->format(DateTimeInterface::RFC3339_EXTENDED),
        ];
        
        $false = [
            "no date, char" => "a",
            "no date, deformed" => "2020-02.02",
        ];
        
        $result = \array_merge(
            \array_map(static fn($s) => [$s, true], $true),
            \array_map(static fn($s) => [$s, false], $false),
        );
        
        return $result;
    }
    
    /**
     * @dataProvider provideListWithLength
     * @param string $list
     * @return void
     */
    public function testContainsList(string $list): void {
        self::assertTrue(StringParser::containsList($list));
        self::assertFalse(StringParser::containsRange($list));
        self::assertFalse(StringParser::containsStandalone($list));
    }
    
    /**
     * @dataProvider provideRange
     * @param string $list
     * @return void
     */
    public function testContainsRange(string $list): void {
        self::assertFalse(StringParser::containsList($list));
        self::assertTrue(StringParser::containsRange($list));
        self::assertFalse(StringParser::containsStandalone($list));
    }
    
    /**
     * @dataProvider provideStandalone
     * //@dataProvider provideInvalidRange
     * @param string $list
     * @return void
     */
    public function testContainsStandalone(string $list): void {
        self::assertFalse(StringParser::containsList($list));
        self::assertFalse(StringParser::containsRange($list));
        self::assertTrue(StringParser::containsStandalone($list));
    }
    
    /**
     * @dataProvider provideRange
     * @dataProvider provideStandalone
     * @param string $list
     * @return void
     */
    public function testNotContainsList(string $list): void {
        self::assertFalse(StringParser::containsList($list));
    }
    
    /**
     * @dataProvider provideListWithLength
     * //@dataProvider provideInvalidRange
     * @dataProvider provideStandalone
     * @param string $list
     * @return void
     */
    public function testNotContainsRange(string $list): void {
        self::assertFalse(StringParser::containsRange($list));
    }
    
    /**
     * @dataProvider provideListWithLength
     * @dataProvider provideRange
     * @param string $list
     * @return void
     */
    public function testNotContainsStandalone(string $list): void {
        self::assertFalse(StringParser::containsStandalone($list));
    }
    
    /**
     * @dataProvider provideInvalidRange
     * @param string $deformedRange
     * @return void
     */
    public function testInvalidRange(string $deformedRange): void {
        self::assertFalse(StringParser::containsList($deformedRange));
        self::assertFalse(StringParser::containsRange($deformedRange));
        self::assertTrue(StringParser::containsStandalone($deformedRange));
    }
    
    /**
     * @dataProvider provideListWithLength
     * @param string $list
     * @param int    $length
     * @return void
     */
    public function testSplitAList(string $list, int $length): void {
        assert(StringParser::containsList($list));
        
        $split = StringParser::splitList($list);
        self::assertCount($length, $split);
    }
    
    /**
     * @dataProvider provideRange
     * @dataProvider provideStandalone
     * @param string $notList
     * @return void
     */
    public function testSplitNotAList(string $notList): void {
        assert(StringParser::containsList($notList) === false);
        $expectedCount = ($notList === "") ? 0 : 1;
        
        $split = StringParser::splitList($notList);
        self::assertCount($expectedCount, $split);
    }
    
    /**
     * @dataProvider provideRange
     * @param string $range
     * @param array  $expectedSplit
     * @return void
     */
    public function testSplitRange(string $range, array $expectedSplit): void {
        assert(StringParser::containsRange($range));
        
        $split = StringParser::splitRange($range);
        self::assertSame($expectedSplit, $split);
    }
    
    /**
     * @dataProvider provideIntegerTestables
     * @param string $integerCandidate
     * @param bool   $expectation
     * @return void
     */
    public function testPositiveInteger(string $integerCandidate, bool $expectation): void {
        try {
            StringParser::parse_positiveInt($integerCandidate);
            self::assertTrue($expectation);
        } catch (\Exception $e) {
            self::assertFalse($expectation);
        }
    }
    
    /**
     * @dataProvider provideDateTestables
     * @param string $dateCandidate
     * @param bool   $expectation
     * @return void
     */
    public function testDate(string $dateCandidate, bool $expectation): void {
        $timezone= new \DateTimeZone(\date_default_timezone_get());
        
        try {
            $date = StringParser::parse_date($dateCandidate);
            self::assertTrue($expectation);
            self::assertEquals($timezone, $date->getTimezone());
            
        } catch (\Exception $e) {
            self::assertFalse($expectation);
        }
    }
    
    
    /**
     * @dataProvider provideDateTestables
     * @param string $rangeAble
     * @param bool   $expectation
     * @return void
     */
    public function testMakeRangeWherePossible(string $rangeAble, bool $expectation): void {
        $RE_outputFormat = "/^(\d*|-\d+)\.{2}(\d*|-\d+)$/u";
        $processAndAssertOutputFormat = static function (string $input) use ($RE_outputFormat): void {
            $output = StringParser::ensureIsDateRange($input);
            self::assertRegExp($RE_outputFormat, $output);
        };
        
        try {
            $processAndAssertOutputFormat($rangeAble);
            $processAndAssertOutputFormat($rangeAble . StringParser::SPLITTER_range);
            $processAndAssertOutputFormat(StringParser::SPLITTER_range . $rangeAble);
            $processAndAssertOutputFormat($rangeAble . StringParser::SPLITTER_range . $rangeAble);
            StringParser::ensureIsDateRange($rangeAble);
            
        } catch (\Exception $e) {
            self::assertFalse($expectation);
        }
    }
    
    /**
     * @dataProvider provideInvalidRange
     * @dataProvider provideListWithLength
     * @dataProvider provideStandalone
     * @param string $notRange
     * @return void
     */
    public function testMakeRangeNotPossible(string $notRange): void {
        $this->testMakeRangeWherePossible($notRange, false);
    }
    
    /**
     * @return void
     * @throws \Exception
     */
    public function testDateWithCustomInt(): void {
        $now = static fn() => (new \DateTimeImmutable())->getTimestamp();
        
        $testSet = [0, $now()];
        for ($i=0; $i<10; $i++) {
            $timestamp = \random_int(0, $now());
            $testSet[] = $timestamp;
        }
    
        foreach ($testSet as $timestamp) {
            $timestampInt = StringParser::parse_date((string)$timestamp);
            $timestampAtSign = StringParser::parse_date("@" . $timestamp);
            self::assertEquals($timestampInt->getTimezone(), $timestampAtSign->getTimezone());
            self::assertEquals($timestampInt->getTimestamp(), $timestampAtSign->getTimestamp());
        }
    }
    
    
}
