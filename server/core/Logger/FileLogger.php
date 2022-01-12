<?php

namespace core\logger;

use core\FileSystem\FileSystemInterface;
use core\Logger\LoggerInterface;
use core\Logger\LoggerTrait;

class FileLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var array<string>
     */
    protected array $buffer = [];

    protected FileSystemInterface $fileSystem;
    protected string $file;

    public function __construct(FileSystemInterface $fileSystem, string $file = 'all.log')
    {
        $this->fileSystem = $fileSystem;
        $this->file = $file;
    }

    public function log($level, $message, array $context = array())
    {
        $timeZone = new \DateTimeZone(TIME_ZONE);
        $dateTime = new \DateTime('now', $timeZone);
        $level = strtoupper($level);
        $context = json_encode($context);

        $this->buffer[] = "[{$dateTime->format('Y-m-d G:i:s')}] [$level] $message $context\r\n";

        if (count($this->buffer) > 500) {
            foreach ($this->buffer as $str) {
                $this->fileSystem->append($this->file, $str);
            }
        }
    }

    public function __destruct()
    {
        foreach ($this->buffer as $str) {
            $this->fileSystem->append($this->file, $str);
        }
    }
}
