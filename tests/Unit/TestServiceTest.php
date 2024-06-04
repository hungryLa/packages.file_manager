<?php

namespace Niyama\FileManager\Tests\Unit;

use Niyama\FileManager\Facades\FileManager;
use Niyama\FileManager\Tests\TestCase;


class TestServiceTest extends TestCase
{
    public function test_sum(): void
    {
        $this->assertEquals(10, FileManager::sum(5,5));
    }
}