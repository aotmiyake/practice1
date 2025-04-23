<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'product_name' => 'required | max:255',
            'company_id' => 'required | max:255',
            'price' => 'required | max:255',
            'stock' => 'required | max:255',
            'comment' => 'max:10000',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        
    }
    public function attributes(){
    return [
        'product_name' => '商品名',
        'company_id' => 'メーカー名',
        'price' => '値段',
        'stock' => '在庫数',
        'comment' => 'コメント',
        'image' => '商品画像', 
        ];
    }
    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'company_id.required' => ':attributeは必須項目です。',
            'company_id.exists' => '選択された:attributeは存在しません。',
            'price.required' => ':attributeは必須項目です。',
            'price.numeric' => ':attributeは数値で入力してください。',
            'stock.required' => ':attributeは必須項目です。',
            'stock.integer' => ':attributeは整数で入力してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '対応形式は jpeg, png, jpg, gif です。',
            'image.max' => '画像サイズは最大2MBです。',
            'comment.max' => ':attributeは:max字以内で入力してください。',
        ];
    }
}
