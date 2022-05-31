<?php

declare(strict_types=1);

namespace Tests\Data;

class CreateArticleDataProvider extends AbstractDataProvider
{
    private function provideInvalidArticleCreateInput(): array
    {
        return [
            'title is missing'                              => [
                'data'   => [
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
                'errors' => [
                    'title' => ['The title field is required.'],
                ],
            ],
            'title is empty string'                         => [
                'data'   => [
                    'title'      => '',
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
                'errors' => [
                    'title' => ['The title field is required.'],
                ],
            ],
            'title is integer'                              => [
                'data'   => [
                    'title'      => $this->faker->randomNumber(),
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
                'errors' => [
                    'title' => ['The title must be a string.'],
                ],
            ],
            'title is only 2 characters'                    => [
                'data'   => [
                    'title'      => 'ls',
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
                'errors' => [
                    'title' => ['The title must be at least 3 characters.'],
                ],
            ],
            'title is more than 255 characters'             => [
                'data'   => [
                    'title'      => $this->faker->words(256, true),
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
                'errors' => [
                    'title' => ['The title must not be greater than 255 characters.'],
                ],
            ],
            'body is missing'                               => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'categories' => [1],
                ],
                'errors' => [
                    'body' => ['The body field is required.'],
                ],
            ],
            'body is empty string'                          => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => '',
                    'categories' => [1],
                ],
                'errors' => [
                    'body' => ['The body field is required.'],
                ],
            ],
            'body is integer'                               => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->randomNumber(),
                    'categories' => [1],
                ],
                'errors' => [
                    'body' => ['The body must be a string.'],
                ],
            ],
            'body is 2 only characters'                     => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => 'sd',
                    'categories' => [1],
                ],
                'errors' => [
                    'body' => ['The body must be at least 3 characters.'],
                ],
            ],
            'body is more than 1000 characters'             => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->words(1001, true),
                    'categories' => [1],
                ],
                'errors' => [
                    'body' => ['The body must not be greater than 1000 characters.'],
                ],
            ],
            'categories is missing'                         => [
                'data'   => [
                    'title' => $this->faker->sentence(),
                    'body'  => $this->faker->text(),
                ],
                'errors' => [
                    'categories' => ['The categories field is required.'],
                ],
            ],
            'categories is empty array'                     => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->text(),
                    'categories' => [],
                ],
                'errors' => [
                    'categories' => ['The categories field is required.'],
                ],
            ],
            'one of the categories provided does not exist' => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->text(),
                    'categories' => [1, 2],
                ],
                'errors' => [
                    'categories.1' => ['The selected categories.1 is invalid.'],
                ],
            ],
            'categories provided are not distinct'          => [
                'data'   => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->text(),
                    'categories' => [1, 1],
                ],
                'errors' => [
                    'categories.0' => ['The categories.0 field has a duplicate value.'],
                    'categories.1' => ['The categories.1 field has a duplicate value.'],
                ],
            ],
        ];
    }

    private function provideValidArticleCreateInput(): array
    {
        return [
            'valid data' => [
                'data' => [
                    'title'      => $this->faker->sentence(),
                    'body'       => $this->faker->text(),
                    'categories' => [1],
                ],
            ],
        ];
    }
}
