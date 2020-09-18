<?php

namespace Modules\Page\Http\Controllers;

use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;

class HomeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = [];
        $category = Category::get();
        foreach ($category as $setCategory) {
            $product = Product::join('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->where('product_categories.category_id', $setCategory->id)
                        ->orderBy('id', 'desc')
                        ->limit(10)
                        ->get();
            $setData = array(
                'title' => $setCategory->name,
                'category_slug' => $setCategory->slug,
                'data' => $product
            );
            array_push($products, $setData);
        }
        return view('public.home.index', compact('products'));
    }
}
