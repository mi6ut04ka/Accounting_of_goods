<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('active_link')) {
    function activeLink(string $name): bool
    {
        return Route::is($name);
    }
}
