<?php

namespace ctbuh\Cloudinary;

class UrlStruct
{
    public $cloud_name = null;
    public $resource_type = 'image';
    public $type = 'upload';
    public $transformations = null;
    public $version = null;
    public $public_id = null;
    public $format = null;

    public function __construct($array)
    {
        foreach ((array)$array as $key => $value) {

            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}