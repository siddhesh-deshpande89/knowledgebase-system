<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase, WithFaker;

    protected string $fakeCreatedTimeString = '2021-01-01T12:26:00.000000Z';

    protected function setFakeTime()
    {
        Carbon::setTestNow(Carbon::createSafe(2021, 1, 1, 12, 26, 00));
    }
}
