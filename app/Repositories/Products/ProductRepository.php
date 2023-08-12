<?php

namespace App\Repositories\Products;

use App\Models\Product;

use App\Repositories\Products\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function create($request)
    {
        return Product::create($request);
    }

    public function update($request, $id)
    {
        return Product::find($id)->update($request);
    }

    public function delete($id)
    {
        return Product::find($id)->delete();
    }
}
