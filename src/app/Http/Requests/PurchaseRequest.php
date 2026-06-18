<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required'],
            'shipping_postal_code' => ['required'],
            'shipping_address' => ['required'],
            'shipping_building' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postal_code.required' => '配送先郵便番号を入力してください',
            'shipping_address.required' => '配送先住所を入力してください',
        ];
    }
}
