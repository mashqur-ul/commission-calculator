<?php


namespace Commission\Calculator;


use Commission\Calculator\Exceptions\FileNotFoundException;
use Commission\Calculator\Exceptions\InvalidFileExtensionException;
use SplFileInfo;

class FileReader
{
    /**
     * @var
     */
    private $inputStream;
    /**
     * @var
     */
    private $file;

    /**
     * @param $file
     * @return $this
     */
    public function setFilePath($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return false|string
     * @throws FileNotFoundException
     * @throws InvalidFileExtensionException
     */
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

    /**
     * @return array|false|string[]
     * @throws FileNotFoundException
     * @throws InvalidFileExtensionException
     */
    public function getData()
    {
        $data = [];

        if (!empty($this->inputStream = $this->readFile())) {
            return explode("\n", $this->inputStream);
        }

        return $data;
    }
}