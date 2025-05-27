<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => '商品名は必須です',
            'product_name.string' => '商品名は文字列で入力してください',
            'product_name.max' => '商品名は255文字以内で入力してください',

            'company_id.required' => '会社名は必須です',
            'company_id.exists' => '選択された会社名は無効です',

            'price.required' => '価格は必須です',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',

            'stock.required' => '在庫数は必須です',
            'stock.integer' => '在庫数は整数で入力してください',
            'stock.min' => '在庫数は0個以上で入力してください',

            'comment.string' => 'コメントは文字列で入力してください',

            'img_path.image' => '画像ファイルを選択してください',
            'img_path.max' => '画像ファイルは2MB以内でアップロードしてください',
        ];
    }
}
