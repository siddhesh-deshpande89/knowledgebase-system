<?php

declare(strict_types=1);

namespace Tests\Data;

class RateArticleDataProvider extends AbstractDataProvider
{
    private function provideInvalidRateArticleInput(): array
    {
        return [
            'article id is missing'      => [
                'data'   => [
                    'rating' => $this->randomRating(),
                ],
                'errors' => [
                    'article_id' => ['The article id field is required.'],
                ],
            ],
            'article id is empty string' => [
                'data'   => [
                    'article_id' => '',
                    'rating'     => $this->randomRating(),
                ],
                'errors' => [
                    'article_id' => ['The article id field is required.'],
                ],
            ],
            'article id does not exist'  => [
                'data'   => [
                    'article_id' => 2,
                    'rating'     => $this->randomRating(),
                ],
                'errors' => [
                    'article_id' => ['The selected article id is invalid.'],
                ],
            ],
            'article id is string'       => [
                'data'   => [
                    'article_id' => $this->faker->word(),
                    'rating'     => $this->randomRating(),
                ],
                'errors' => [
                    'article_id' => ['The article id must be an integer.'],
                ],
            ],
            'rating is missing'          => [
                'data'   => [
                    'article_id' => 1,
                ],
                'errors' => [
                    'rating' => ['The rating field is required.'],
                ],
            ],
            'rating is zero'             => [
                'data'   => [
                    'article_id' => 1,
                    'rating'     => 0,
                ],
                'errors' => [
                    'rating' => ['The rating must be at least 1.'],
                ],
            ],
            'rating is more than five'   => [
                'data'   => [
                    'article_id' => 1,
                    'rating'     => rand(6, 10),
                ],
                'errors' => [
                    'rating' => ['The rating must not be greater than 5.'],
                ],
            ],
        ];
    }

    private function randomRating()
    {
        return rand(1, 5);
    }
}
