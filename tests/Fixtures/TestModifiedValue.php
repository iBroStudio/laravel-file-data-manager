<?php

namespace IBroStudio\FileContentManager\Fixtures;

use Vendor\Package\Namespace\Class1;
use Vendor\Package\Namespace\Class2;

class Test
{
    protected string $testValue = 'tata';

    protected array $testArray1 = [
        'ArrayValue',
    ];

    protected array $testArray2 = [
        'ArrayValue',
    ];

    protected array $testArray3 = [
        ArrayValue::class,
    ];

    protected array $testArray4 = [
        ArrayValue1::class,
        ArrayValue2::class,
        ArrayValue3::class,
    ];

    protected array $testArray5 = [
    ];

    protected array $testArray6 = [
        'test-styles' => __DIR__ . '/../dist/test.css',
    ];
}
