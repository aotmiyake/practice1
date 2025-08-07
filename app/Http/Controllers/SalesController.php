<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        return DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($product->stock < 1) {
                return response()->json(['error' => '在庫がありません'], 400);
            }

            $product->decrement('stock');

            $sale = Sale::create([
                'product_id' => $product->id,
            ]);

            return response()->json(['message' => '購入完了', 'sale' => $sale], 201);
        });
    }
}
