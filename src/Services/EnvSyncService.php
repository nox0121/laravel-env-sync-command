<?php

namespace Nox0121\LaravelEnvSyncCommand\Services;

use Nox0121\LaravelEnvSyncCommand\Services\EnvLoader as Loader;

class EnvSyncService
{
    /**
     * Get the differences.
     *
     * @param string $source
     * @param string $destination
     */
    public function getDiff($source, $destination)
    {
        if ($source && $destination) {
            $this->ensureFileExists($source, $destination);

            $sourceValues = (new Loader($source))->load();
            $destinationValues = (new Loader($destination))->load();

            $diffKeys = array_diff(array_keys($sourceValues), array_keys($destinationValues));
            return array_filter($sourceValues, function ($key) use ($diffKeys) {
                return in_array($key, $diffKeys);
            }, ARRAY_FILTER_USE_KEY);
        }
        return false;
    }

    /**
     * Append a new par of key/value to an env resource
     *
     * @param string|null $resource resource where is located the env content
     */
    public function append($resource, $key, $value)
    {
        $lastChar = substr(file_get_contents($resource), -1);

        $prefix = "";
        if ($lastChar != "\n" && $lastChar != "\r" && strlen($lastChar) == 1) {
            $prefix = PHP_EOL;
        }

        $str = $prefix;
        if (is_array($value)) {
            if (isset($value['comments'])) {
                foreach ($value['comments'] as $val) {
                    $str .= $val . PHP_EOL;
                }
            }
            $str .= $key . '=' . $value['name'];
        } else {
             $str .= $key . '=' . $value;
        }

        file_put_contents($resource, $str, FILE_APPEND);
    }

    /**
     * Make sure that files exist
     *
     * @param string $files
     */
    private function ensureFileExists(...$files)
    {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new \Exception(sprintf("%s must exists", $file));
            }
        }
    }
}
