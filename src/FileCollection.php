<?php

namespace Live\Collection;

/**
 * File collection
 *
 * @package Live\Collection
 */

class FileCollection implements CollectionInterface
{
    /**
     * @var string
     */
    private $filepath = "arquivo.txt";

    /**
     * @var bool|resource
     */
    private $file;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->file = fopen($this->filepath, "w+");
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        
        if (!$this->has($index)) {
            return $defaultValue;
        }

        $data = $this->fileExplode();

        $key = array_search($index, array_column($data, 0));

        if (time() > $data[$key][2]) {
            return null;
        }

        return $data[$key][1];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, int $timeExpired = 60)
    {
        $timeExpired += time();

        if (is_array($value)) {
            $value = implode(";", $value);
        }

        $cache = $this->fileExplode();

        $data = array($index, $value, $timeExpired, "\n");

        $key = array_search($index, array_column($cache, 0));

        if (!$key && $key !== 0) {
            $newFile = implode("|", $data);
            fwrite($this->file, $newFile);
            return;
        }

        $cache[$key] = $data;

        $this->clean();

        foreach ($cache as $i => $v) {
            $newFile = implode("|", $cache[$i]);
            fwrite($this->file, $newFile);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        $data = $this->fileExplode();

        $key = array_search($index, array_column($data, 0));

        return ($key || $key === 0) ? true : false;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        $data = file($this->filepath);
        return count($data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        file_put_contents($this->filepath, "");
    }

     /**
     * Returns in arrays the values ​​saved in the file
     * @return array
     */
    private function fileExplode()
    {
        $file = file($this->filepath);

        $map = function ($value) {
            return explode("|", $value);
        };
        
        return array_map($map, $file);
    }
}
