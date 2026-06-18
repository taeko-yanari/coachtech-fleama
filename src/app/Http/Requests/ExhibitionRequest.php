<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ExhibitionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required','max:255'],
            'image' => [session('temp_image_path') ? 'nullable' : 'required', 'mimes:png,jpeg'],
            'category_ids' => ['required'],
            'condition' => ['required'],
            'price' => ['required', 'integer', 'min:0'],
            'brand_name' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '255文字以内で入力してください',
            'image.required' => '画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'category_ids.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0円以上で入力してください',
        ];
    }
}
