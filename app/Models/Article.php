<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Company;

class Article extends Model
{
    use HasFactory;

    // 商品一覧取得（検索機能付き）
    public function searchList($keyword, $company_id)
    {
        return Product::with('company')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('product_name', 'LIKE', "%{$keyword}%");
            })
            ->when($company_id, function ($query, $company_id) {
                return $query->where('company_id', $company_id);
            })
            ->get();
    }

    // 全ての会社を取得
    public function getListCompanies()
    {
        return Company::all();
    }

    // 商品削除
    public function deleteProduct($id)
    {
        return Product::destroy($id);
    }

    // 商品詳細取得（会社情報含む）
    public function getProductDetail($id)
    {
        return Product::with('company')->find($id);
    }

    // 商品取得（更新画面用）
    public function getProductById($id)
    {
        return Product::find($id);
    }

    // 商品登録
    public function registProducts($request)
    {
        // 画像ファイルの保存処理
        $img_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $img_path = $image->store('images', 'public'); // storage/app/public/images に保存
        }

        return Product::create([
            'company_id'    => $request->company_id,
            'product_name'  => $request->product_name,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'comment'       => $request->comment,
            'img_path'      => $img_path, // 画像パスを保存
        ]);
    }

    // 商品更新
    public function updateProducts($request, $id)
    {
        $product = Product::findOrFail($id);

        // 画像がアップロードされていれば処理
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('images', 'public');
            $product->img_path = $path;
        }

        // 他の情報を更新
        $product->company_id    = $request->company_id;
        $product->product_name  = $request->product_name;
        $product->price         = $request->price;
        $product->stock         = $request->stock;
        $product->comment       = $request->comment;

        return $product->save();  // true/false を返す
    }
}