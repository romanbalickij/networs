<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    public function refreshDatabase()
    {
        global $dontReloadTestDb;

        if (isset($dontReloadTestDb) && $dontReloadTestDb) {
            return true;
        } else {
            $this->usingInMemoryDatabase()
                ? $this->refreshInMemoryDatabase()
                : $this->refreshTestDatabase();
        }
    }

}
