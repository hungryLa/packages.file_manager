<?php

namespace Niyama\FileManager\Services;

class TestService
{
    public function sum(int|float $a, int|float $b): int|float
    {
        return  $a + $b;
    }
}