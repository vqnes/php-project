<?php

namespace core\FileSystem;

class FileSystem implements FileSystemInterface
{
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function isFile(string $file): bool
    {
        return is_file($file);
    }

    public function get(string $path, bool $lock = false): string
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new \Exception("File does not exist at path $path");
    }

    public function sharedGet(string $path): string
    {
        $contents = '';

        $handle = fopen($path, 'rb');

        if ($handle) {
            try {
                if (flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);

                    $contents = fread($handle, $this->size($path) ?: 1);

                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }

        return $contents;
    }

    public function getRequire(string $path)
    {
        if ($this->isFile($path)) {
            return require $path;
        }

        throw new \Exception("File does not exist at path $path");
    }

    public function put(string $path, string $contents, bool $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    public function prepend(string $path, string $data): int
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    public function append(string $path, string $data): int
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    public function delete(string $path): bool
    {
        $success = true;

        try {
            if (!unlink($path)) {
                $success = false;
            }
        } catch (\Exception $e) {
            $success = false;
        }

        return $success;
    }

    public function lines(string $path, bool $emptyLines = false)
    {
        return file($path, $emptyLines ? 0 : FILE_SKIP_EMPTY_LINES);
    }

    public function name(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public function dirname(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    public function type(string $path): string
    {
        return filetype($path);
    }

    public function mimeType(string $path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    public function size(string $path): int
    {
        return filesize($path);
    }
}
