<?php

namespace KnowledgeSystem\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SearchArticleRequest extends FormRequest
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


    public function rules()
    {
        return [
            'keyword' => 'required|string',
        ];
    }
}
