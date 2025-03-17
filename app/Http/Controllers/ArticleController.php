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
    //一覧表示・商品検索
    public function showList(Request $request){
        $keyword = $request->input('keyword', '');
        $company_id = $request->input('company_id', ''); // 会社IDの検索用
        
        // 会社一覧を取得
        $companies = Company::all(); 
    
        // 商品を検索（会社名も取得）
        $articles = Product::with('company')  // 会社情報を一緒に取得
                    ->when($keyword, function($query, $keyword) {
                        return $query->where('product_name', 'LIKE', "%{$keyword}%");
                    })
                    ->when($company_id, function($query, $company_id) {
                        return $query->where('company_id', $company_id);
                    })
                    ->get();
    
        return view('list', [
            'articles' => $articles,
            'keyword' => $keyword,
            'companies' => $companies,
            'company_id' => $company_id 
        ]);
    }
    
    public function showRegistForm() {
        return view('regist');
    }

    //リストから商品削除
    public function delete($id) {
        $deleteId = DB::table('products')->where('id', $id)->delete();
        return redirect(route('list'));
    }

    //商品詳細表示
    public function detail($id) {
        $detail = Product::with('company')->find($id);
        return view('detail', compact('detail'));
    }

    // 情報更新画面表示
    public function update($id) {
        $update = DB::table('products')->find($id);
        $companies = Company::all();  // 会社一覧を取得
        return view('update', compact('update', 'companies'));
    }

    public function registSubmit(ArticleRequest $request) {

        // トランザクション開始
        DB::beginTransaction();
    
        try {
            // 登録処理呼び出し
            $model = new Article();
            $model->registProducts($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
    
        // 処理が完了したらregistにリダイレクト
        return redirect(route('regist'));
    }

    //情報更新
    public function updateSubmit(ArticleRequest $request , $id) {
        DB::beginTransaction();
        try {
            $update = new Article();
            $update->updateProducts($request, $id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
        return redirect(route('update',['id' => $id]));
    }

    //企業情報取得
    public function getCompanies(){
        return DB::table('companies')->get();
    }

    //ログイン必須にする
    public function __construct(){
        $this->middleware('auth');
    }
}