<?php

namespace Commission\Calculator\Tests;

use Commission\Calculator\Exceptions\FileNotFoundException;
use Commission\Calculator\Exceptions\InvalidFileExtensionException;
use Commission\Calculator\FileReader;
use PHPUnit\Framework\TestCase;

final class FileReaderTest extends TestCase
{
    private $fileReader;

    protected function setUp(): void
    {
        $this->fileReader = new FileReader();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->fileReader);
        parent::tearDown();
    }

    public function testFileNotFoundExceptionIsRaisedIfFileNotFound()
    {
        $reader = $this->fileReader->setFilePath('invalid.txt');
        $this->expectException(FileNotFoundException::class);
        $reader->getData();

    }

    public function testShouldReturnEmptyArrayFileIsEmpty()
    {
        $testFile = __DIR__.'/empty_test_file.txt';
        $reader = $this->fileReader->setFilePath($testFile);
        $data = $reader->getData();
        $expected = [];
        $this->assertTrue($data===$expected);
    }

    public function testInvalidFileExtensionExceptionIsRaisedIfFileExtensionIsNotTxt()
    {
        $testFile = __DIR__.'/test.json';
        $reader = $this->fileReader->setFilePath($testFile);
        $this->expectException(InvalidFileExtensionException::class);
        $reader->getData();
    }

    public function testShouldReturnFileData()
    {
        $testFile = __DIR__.'/input.txt';
        $expected = explode("\n", file_get_contents($testFile));
        $data = $this->fileReader->setFilePath($testFile)->getData();
        $this->assertTrue($data === $expected);
    }
}