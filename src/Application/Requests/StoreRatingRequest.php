<?php

namespace KnowledgeSystem\Application\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class StoreRatingRequest extends FormRequest
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
     * @OA\Property(format="integer", default="1", description="article id", property="article_id"),
     * @OA\Property(format="string", default="1", description="rating between 1 to 5", property="rating"),
     */
    public function rules()
    {
        return [
            'article_id' => 'required|int|exists:articles,id',
            'rating' => 'required|int|min:1|max:5'
        ];
    }
}
