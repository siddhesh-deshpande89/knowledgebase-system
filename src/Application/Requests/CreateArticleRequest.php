<?php

namespace KnowledgeSystem\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class CreateArticleRequest extends FormRequest
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
     * @OA\Property(format="string", default="test title", description="article title", property="title"),
     * @OA\Property(format="string", default="test body", description="article body", property="body"),
    @OA\Property(
    property="categories[]",
    description="Category id",
    type="array",
    @OA\Items(type="number"),
    ),
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'body' => 'required|string|min:3|max:1000',
            "categories"    => "required|array|min:1",
            "categories.*"  => "required|distinct|min:1|exists:categories,id",
        ];
    }
}
