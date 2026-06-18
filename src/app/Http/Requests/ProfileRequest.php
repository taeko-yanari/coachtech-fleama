<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends FormRequest
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
            'profile_image_path' => ['nullable','mimes:png,jpeg'],
            'name' => ['required', 'max:20'],
            'postal_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['nullable']
            ];
    }

    public function messages()
    {
        return [
            'profile_image_path.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号をハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->hasFile('profile_image_path') && $this->file('profile_image_path')->isValid()) {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));
            }
        $tempPath = $this->file('profile_image_path')->store('temp', 'public');
        session(['temp_image_path' => $tempPath]);
        }
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('profile_image_path')) {
            Storage::disk('public')->delete(session('temp_image_path'));
            session()->forget('temp_image_path');

            parent::failedValidation($validator);
        }
    }


}