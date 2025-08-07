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

    public function updateSubmit(ArticleRequest $request, $id){
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

    // Ajax検索
    public function searchAjax(Request $request){
        $articleModel = new Article();
        $keyword = $request->input('keyword', '');
        $company_id = $request->input('company_id', '');
        $price_min = $request->input('price_min');
        $price_max = $request->input('price_max');
        $stock_min = $request->input('stock_min');
        $stock_max = $request->input('stock_max');

        // 価格と在庫数の条件を含めて検索
        $articles = $articleModel->searchList($keyword, $company_id, $price_min, $price_max, $stock_min, $stock_max);

        return response()->json($articles);
    }

    // Ajax削除
    public function deleteAjax($id){
        try {
            (new Article())->deleteProduct($id);
            return response()->json(['message' => '削除成功']);
        } catch (\Exception $e) {
            \Log::error('商品削除エラー(Ajax): ' . $e->getMessage());
            return response()->json(['message' => '削除失敗'], 500);
        }
    }
}