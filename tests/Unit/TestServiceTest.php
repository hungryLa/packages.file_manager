<?php

namespace Niyama\FileManager\Tests\Unit;

use Niyama\FileManager\Facades\Test;
use Niyama\FileManager\Tests\TestCase;


class TestServiceTest extends TestCase
{
    public function test_sum(): void
    {
        $this->assertEquals(10, Test::sum(5,5));
    }
}