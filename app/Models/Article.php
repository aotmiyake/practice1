<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    public function getListProducts() {
        // productsテーブルからデータを取得
        $articles = DB::table('products')->get();
        return $articles;
    }

    public function getListCompanies() {
        // companyテーブルからデータを取得
        $companies = DB::table('companies')->get();
        return $companies;
    }

    public function searchProducts($keyword) {
        // productsテーブルから検索したデータを取得
        return DB::table('products')->where('product_name', 'like', '%'.$keyword.'%')->get();
    }

    public function registProducts($data) {
        // 登録処理
        DB::table('products')->insert([
            'company_id' => $data->company_id,
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
        ]);
    }

    public function updateProducts($request ,$id) {
        // 更新処理
        DB::table('products')->where('products.id','=',$id)->update([
            'company_id' => $request->company_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
        ]);
    }
}
