<?php

namespace App\Libs\IO;


interface OutputFileInterface
{
    public function setData(\Traversable $data);
    public function setFilePrefix(string $name);
    public function getFilePrefix(): string;

    /**
     * Returns filename of output
     *
     * @return string
     */
    public function output(): string;
}