<?php

namespace Vorkfork\File;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

class File extends Filesystem
{
    public Finder $finder;
    protected string $root;
    protected string $path;
    protected string $fileName;
    protected string $resources;

    public function __construct($root = '../', $fileName = ''){
        $this->finder = new Finder();
        $this->root = Path::normalize(realpath($root));
        $this->fileName = $fileName;
        $this->path = Path::normalize($this->root . DIRECTORY_SEPARATOR . $this->fileName);
        return $this;
    }

    public function get($path = null)
    {
        $path = !is_null($path) ? $this->root . $path : $this->path;
        if (!$this->exists($path)) {
            throw new FileNotFoundException($path);
        }
        return file_get_contents($path);
    }

    public function glob($path = null)
    {
        $path = !is_null($path) ? $this->root . $path : $this->path;
        if (!$this->exists($path)) {
            throw new FileNotFoundException();
        }
        return $this->finder->name('*.json')->in($path);
    }

    public function create($path = null){
        $path = !is_null($path) ? $path : $this->path;
        $this->touch($path);
    }

    public function contentArray(): array
    {
        return file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function dump($contents){
        $this->dumpFile($this->path, $contents);
    }
}