<?php


namespace Commission\Calculator;


use Commission\Calculator\Exceptions\FileNotFoundException;
use Commission\Calculator\Exceptions\InvalidFileExtensionException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use SplFileInfo;

class FileReader
{
    private $inputStream;
    private $file;

    public function setFilePath($file)
    {
        $this->file = $file;
        return $this;
    }

    private function readFile()
    {
        if (!file_exists($this->file)) {
            throw new FileNotFoundException();
        }

        $spl = new SplFileInfo($this->file);
        if ($spl->getExtension() != 'txt') {
            throw new InvalidFileExtensionException();
        }

        return file_get_contents($this->file);
    }

    public function getData()
    {
        $data = [];

        if (!empty($this->inputStream = $this->readFile())) {
            return explode("\n", $this->inputStream);
        }

        return $data;
    }
}