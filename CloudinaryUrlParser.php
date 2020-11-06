<?php

namespace ctbuh\Cloudinary;

class CloudinaryUrlParser
{
    /**
     * @param $url
     * @return UrlStruct
     */
    public function parse($url)
    {
        $parsed = cl_parse_url($url);
        return new UrlStruct($parsed);
    }
}