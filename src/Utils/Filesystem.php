<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/11
 * Time: 19:06
 */

namespace XYLibrary\Utils;


class Filesystem
{
    /**
     * @param $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * @param $path
     * @param bool $lock
     * @return string
     */
    public function get($path, $lock = false)
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * @param $path
     * @return string
     */
    public function sharedGet($path)
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

    /**
     * @param $path
     * @param $contents
     * @param bool $lock
     * @return int
     */
    public function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * @param $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;
        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (\ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }

    public function move($path, $target)
    {
        return rename($path, $target);
    }

    public function copy($path, $target)
    {
        return copy($path, $target);
    }

    public function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function dirname($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param $path
     * @return string
     */
    public function type($path)
    {
        return filetype($path);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    /**
     * @param $path
     * @return int
     */
    public function size($path)
    {
        return filesize($path);
    }

    /**
     * @param $path
     * @return int
     */
    public function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * @param $directory
     * @return bool
     */
    public function isDirectory($directory)
    {
        return is_dir($directory);
    }

    /**
     * @param $path
     * @return bool
     */
    public function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * @param $path
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * @param $file
     * @return bool
     */
    public function isFile($file)
    {
        return is_file($file);
    }

    /**
     * @param $directory
     * @return array
     */
    public function files($directory)
    {
        $glob = glob($directory . DIRECTORY_SEPARATOR . '*');

        if ($glob === false) {
            return [];
        }
        return array_filter($glob, function ($file) {
            return filetype($file) == 'file';
        });
    }
}