<?php

namespace Nox0121\LaravelEnvSyncCommand\Services;

use Nox0121\LaravelEnvSyncCommand\Dotenv\Loader;

class EnvLoader extends Loader
{
    /**
     * Load file in given directory.
     *
     * @return array
     */
    public function load()
    {
        $this->ensureFileIsReadable();
        $lines = $this->readLinesFromFile($this->filePath);

        $finalLines = [];
        foreach ($lines as $key => $line) {
            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
                list($name, $value) = $this->normaliseEnvironmentVariable($line, null);
                $i = 1;
                while (isset($lines[$key - $i]) && $this->isComment($lines[$key - $i])) {
                    $finalLines[$name]['comments'][] = $lines[$key - $i];
                    $i++;
                }
                if (isset($lines[$key - 1])) {
                    if (isset($finalLines[$name]['comments'])) {
                        $finalLines[$name]['comments'] = array_reverse($finalLines[$name]['comments']);
                    }
                    $finalLines[$name]['name'] = $value;
                } else {
                    $finalLines[$name] = $value;
                }
            }
        }

        return $finalLines;
    }

    /**
     * Normalise the given environment variable.
     *
     * @param string $name
     * @param string $value
     *
     * @return array
     */
    protected function normaliseEnvironmentVariable($name, $value)
    {
        list($name, $value) = $this->splitCompoundStringIntoParts($name, $value);
        list($name, $value) = $this->sanitiseVariableName($name, $value);

        $value = $this->resolveNestedVariables($value);

        return array($name, $value);
    }
}
