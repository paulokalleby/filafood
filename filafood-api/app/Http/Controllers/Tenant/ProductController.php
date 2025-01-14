<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;

    public function __construct(ProductService $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        return ProductResource::collection(
            $this->product->getAllProducts(
                (array) $request->all()
            )
        );
    }

    public function store(ProductRequest $request)
    {
        return new ProductResource(
            $this->product->createProduct(
                (array) $request->validated()
            )
        );
    }

    public function show($id)
    {
        return new ProductResource(
            $this->product->findProductById($id)
        );
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->product->updateProduct(
            (array) $request->validated(),
            $id
        );
    }

    public function destroy($id)
    {
        $this->product->deleteProduct($id);
    }
}
