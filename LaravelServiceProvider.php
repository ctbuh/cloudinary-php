<?php

namespace ctbuh\Cloudinary;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require(__DIR__ . '/functions.php');
    }
}