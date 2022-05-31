<?php

namespace KnowledgeSystem\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListArticleRequest extends FormRequest
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
            'sort'      => [Rule::in(['asc', 'desc'])],
            'created_from' => 'date',
            'created_to'   => 'date|after:created_from',
            'views_from' => 'date',
            'views_to'   => 'date|after:views_from',
            'type'      => [Rule::in(['popularity', 'views'])],
        ];
    }
}
