<?php

namespace core\FileSystem;

interface FileSystemInterface
{
    public function exists(string $path): bool;

    public function isFile(string $file): bool;

    public function get(string $path, bool $lock = false): string;

    public function sharedGet(string $path): string;

    /**
     * @param  string $path
     * 
     * @return mixed
     */
    public function getRequire(string $path);

    /**
     * @param  string  $path
     * @param  string  $contents
     * @param  bool  $lock
     * 
     * @return int|bool
     */
    public function put(string $path, string $contents, bool $lock = false);

    /**
     * @param  string  $path
     * 
     * @return bool
     */
    public function delete(string $path): bool;

    public function prepend(string $path, string $data): int;

    public function append(string $path, string $data): int;

    /**
     * @param string $path
     * @param bool   $emptyLines
     * 
     * @return array<string>|false
     */
    public function lines(string $path, bool $emptyLines = false);

    public function name(string $path): string;

    public function dirname(string $path): string;

    public function type(string $path): string;

    /**
     * @param  string $path
     * 
     * @return string|false
     */
    public function mimeType(string $path);

    public function size(string $path): int;
}
