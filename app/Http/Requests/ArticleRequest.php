<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' =>'required|integer',
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'body' => 'required|string',
            'image' =>'required|mimes:jpg,jpeg,png|max:2048',
            'reading_time' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'category_id.required' => 'Kategori alanı boş geçilemez',
            'title.required' => 'Başlık alanı boş geçilemez',
            'body:required' => 'İçerik alanı boş geçilemez',
            'image:required' => 'Resim alanı boş geçilemez',
            'image.mimes' => 'JPEG, JPG veya PNG uzantılı dosyalar yüklenebilir',
        ];
    }
}
