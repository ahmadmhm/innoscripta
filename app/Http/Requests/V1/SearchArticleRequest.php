<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class SearchArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'source' => [
                'array', 'nullable',
            ],
            'authors' => [
                'array', 'nullable',
            ],
            'dateFrom' => [
                'date', 'nullable',
            ],
            'dateTo' => [
                'date', 'nullable',
            ],
        ];
    }
}
