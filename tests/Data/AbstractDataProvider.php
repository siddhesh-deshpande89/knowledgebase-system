<?php

declare(strict_types=1);

namespace Tests\Data;

use Illuminate\Foundation\Testing\WithFaker;

class AbstractDataProvider
{
    use WithFaker;

    public function __construct()
    {
        $this->faker = $this->makeFaker('en_US');
    }

}
