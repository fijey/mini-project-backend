<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Product;

class ProductsExport implements FromCollection
{
    public function collection()
    {
        return Product::all();
    }
}
