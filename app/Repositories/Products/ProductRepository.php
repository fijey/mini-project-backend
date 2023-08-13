<?php

namespace App\Repositories\Products;

use App\Models\Product;

use App\Repositories\Products\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all($request)
    {
        $query = Product::query();

        $filters = $request->filters;
        if ($filters) {
            $filters = json_decode($filters, true);

            if (isset($filters['name']) && $filters['name'] != "") {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['min_price']) && $filters['min_price'] != "") {
                $query->where('price', '>=', $filters['min_price']);
            }

            if (isset($filters['max_price'])&& $filters['max_price'] != "") {
                $query->where('price', '<=', $filters['max_price']);
            }

            if (isset($filters['quantity'])&& $filters['quantity']) {
                $query->where('quantity', '=', $filters['quantity']);
            }

        }

        $products = $query->paginate(50);

        return $products;
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
