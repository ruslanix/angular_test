<?php

namespace Acme\DemoBundle\Model;

class Image
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string Image file name
     */
    public $filename;

    /**
     *
     * @var string Image file
     */
    public $file = null;

    /**
     * String representation for a note
     *
     * @return string
     */
    public function __toString()
    {
        return $this->label;
    }
}
