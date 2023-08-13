<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Products\ProductRepositoryInterface;
use App\Services\ImageService;
use App\Jobs\ProductsExportJob;
use Auth;

class ProductController extends Controller
{
    protected $productRepository;
    protected $imageService;

    public function __construct(ProductRepositoryInterface $productRepository, ImageService $imageService)
    {
        $this->productRepository = $productRepository;
        $this->imageService = $imageService;
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->all($request);
        return response()->json($products, 200);
    }

    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $validate = $request->validate($this->getValidationRules());

        $validate['image'] = $this->imageService->uploadImage($request);

        $create = $this->productRepository->create($validate);

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validate = $request->validate($this->getValidationRules($id));

        $validate['image'] = $this->imageService->uploadImage($request);

        $update = $this->productRepository->update($validate, $id);

        return response()->json(['message' => 'Product updated successfully'], 200);
    }

    public function destroy($id)
    {
        $product = $this->productRepository->delete($id);
        return response()->json(['message' => 'Product Delete successfully'], 200);
    }

    public function export(){

        ProductsExportJob::dispatch(Auth::user()->id);

        return response()->json(['message' => 'Ekspor telah dimulai.'],200);
    }
}
