<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{ 
    public function showList(Request $request) {
        $articleModel = new Article();
        $keyword = $request->input('keyword', '');
        $company_id = $request->input('company_id', '');

        $companies = $articleModel->getListCompanies();
        $articles = $articleModel->searchList($keyword, $company_id);

        return view('list', [
            'articles' => $articles,
            'keyword' => $keyword,
            'companies' => $companies,
            'company_id' => $company_id 
        ]);
    }

    public function showRegistForm() {
        $companies = (new Article())->getListCompanies();
        return view('regist', compact('companies'));
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            (new Article())->deleteProduct($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('商品削除エラー: ' . $e->getMessage());
            return back()->with('error', '削除処理中にエラーが発生しました。');
        }
    
        return redirect(route('list'))->with('success', '商品を削除しました。');
    }

    public function detail($id) {
        $detail = (new Article())->getProductDetail($id);
        return view('detail', compact('detail'));
    }

    public function update($id) {
        $articleModel = new Article();
        $update = $articleModel->getProductById($id);
        $companies = $articleModel->getListCompanies();
        return view('update', compact('update', 'companies'));
    }

    public function registSubmit(ArticleRequest $request) {
        DB::beginTransaction();
        try {
            (new Article())->registProducts($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
        return redirect(route('regist'));
    }

    public function updateSubmit(Request $request, $id)
{
    // バリデーション追加
    $request->validate([
        'product_name' => 'required|string|max:255',
        'company_id' => 'required|integer|exists:companies,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'comment' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'product_name.required' => '商品名は必須です。',
        'company_id.required' => 'メーカーを選択してください。',
        'price.required' => '価格は必須です。',
        'price.numeric' => '価格は数値で入力してください。',
        'stock.required' => '在庫数は必須です。',
        'stock.integer' => '在庫数は整数で入力してください。',
        'image.image' => '画像ファイルを選択してください。',
        'image.mimes' => '対応形式は jpeg, png, jpg, gif です。',
        'image.max' => '画像サイズは最大2MBです。',
    ]);
    DB::beginTransaction();
    try {
        $articleModel = new Article();
        $articleModel->updateProducts($request, $id);
        DB::commit();
        return redirect()->route('update', ['id' => $id])->with('success', '更新しました');
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('更新エラー: ' . $e->getMessage());
        return back()->with('error', '更新に失敗しました');
    }
}

    public function __construct(){
        $this->middleware('auth');
    }
}
