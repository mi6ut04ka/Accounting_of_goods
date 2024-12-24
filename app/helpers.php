<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

if (! function_exists('active_link')) {
    function activeLink(string $name): bool
    {
        return Route::is($name);
    }
}

if (! function_exists('potential_revenue')) {
    function potentialRevenue(): int
    {
        $products = Product::all()->filter(function ($product) {
            return !$product->set;
        });

        $potential_revenue = 0;

        foreach ($products as $product) {
            $potential_revenue += $product->price * $product->in_stock;
        }

        return (int)$potential_revenue;
    }
}

if (! function_exists('count_product')) {
    function CountProduct(): int
    {
        $products = Product::all()->filter(function ($product) {
            return !$product->set;
        });

        $count_product = 0;

        foreach ($products as $product) {
            $count_product += $product->in_stock;
        }

        return (int)$count_product;
    }
}
