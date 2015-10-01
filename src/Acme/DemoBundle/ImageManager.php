<?php

namespace Acme\DemoBundle;

use Acme\DemoBundle\Model\Image;

class ImageManager
{
    const IMAGE_DATA_FILE = 'image_data';
    /** @var array images */
    protected $data = array();

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var string
     */
    protected $rootDir;

    public function __construct($cacheDir, $rootDir)
    {
        $filepath = $cacheDir . '/' . self::IMAGE_DATA_FILE;
        if (file_exists($cacheDir . '/' . self::IMAGE_DATA_FILE)) {
            $data = file_get_contents($cacheDir . '/' . self::IMAGE_DATA_FILE);
            $this->data = unserialize($data);
        }

        $this->cacheDir = $cacheDir;
        $this->rootDir = $rootDir;
    }

    private function flush()
    {
        file_put_contents($this->cacheDir . '/' . self::IMAGE_DATA_FILE, serialize($this->data));
    }

    public function fetch()
    {
        return $this->data;
    }

    public function get($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        return $this->data[$id];
    }

    public function set(Image $image)
    {
        if (null === $image->id) {
            if (empty($this->data)) {
                $image->id = 0;
            } else {
                end($this->data);
                $image->id = key($this->data) + 1;
            }
        }

        $this->processFile($image);

        $this->data[$image->id] = $image;

        $this->flush();
    }

    public function remove($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        unset($this->data[$id]);
        $this->flush();

        return true;
    }

    protected function getImageDir()
    {
        return $this->rootDir . '/../web/images';
    }
    
    protected function buildFullImagePath($filename)
    {
        return  $this->getImageDir() . '/' . $filename;
    }

    protected function processFile(Image $image)
    {
        if (! $image->file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            return;
        }

        if ($image->filename) {
            @unlink($this->buildFullImagePath($image->filename));
        }

        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $image->file;
        $filename = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getImageDir(), $filename);

        $image->filename = $filename;
        $image->file = null;
    }
}
