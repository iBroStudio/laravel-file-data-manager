<?php

//use IBroStudio\FileDataManager\Facades\FileDataManager;

use IBroStudio\FileDataManager\FileDataManager;

uses()->group('php');

it('can load a php file', function () {
    $loaded = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');

    $this->assertEquals(
        $loaded->getContent(),
        file_get_contents(__DIR__ . '/Fixtures/Test.php')
    );
});

it('can find a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->findValue('$testValue')
        ->getValue();

    expect($value)->toBe('toto');
});

it('can find an array value', function ($key, $expectedValue) {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->findArray($key)
        ->getValue();

    expect($value)->toBe($expectedValue);
})->with([
    ['$testArray1', ["'ArrayValue'"]],
    ['$testArray2', ["'ArrayValue'"]],
    ['$testArray3', ['ArrayValue::class']],
    ['$testArray4', ['ArrayValue1::class', 'ArrayValue2::class', 'ArrayValue3::class']],
    ['$testArray5', []],
    ['$testArray6', ["'test-styles'=>__DIR__.'/../dist/test.css'"]],
]);

it('can find a regex value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->findRegex('#(use\s(.*?)\;)#s')
        ->getValue();

    expect($value)->toMatchArray([
        'use Vendor\Package\Namespace\Class1;',
        'use Vendor\Package\Namespace\Class2;',
    ]);
});

it('can replace a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->replaceValue('$testValue', 'tata')
        ->findValue('$testValue')
        ->getValue();

    expect($value)->toBe('tata');
});

it('can add a value to an array', function ($key, $newValue, $expectedValue) {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->addArrayValue($key, $newValue)
        ->findArray($key)
        ->getValue();

    expect($value)->toBe($expectedValue);
})->with([
    ['$testArray1', "'NewValue'", ["'ArrayValue'", "'NewValue'"]],
    ['$testArray2', "'NewValue'", ["'ArrayValue'", "'NewValue'"]],
    ['$testArray3', 'NewValue::class', ['ArrayValue::class', 'NewValue::class']],
    ['$testArray4', 'NewValue::class', ['ArrayValue1::class', 'ArrayValue2::class', 'ArrayValue3::class', 'NewValue::class']],
    ['$testArray5', 'NewValue::class', ['NewValue::class']],
    ['$testArray6', "'test2-styles'=>__DIR__.'/../dist/test2.css'", ["'test-styles'=>__DIR__.'/../dist/test.css'", "'test2-styles'=>__DIR__.'/../dist/test2.css'"]],
]);

it('can add a regex value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
        ->addRegexValue('#(use\s(.*?)\;)#s', 'use Vendor\Package\Namespace\Class3;')
        ->findRegex('#(use\s(.*?)\;)#s')
        ->getValue();

    expect($value)->toMatchArray([
        'use Vendor\Package\Namespace\Class1;',
        'use Vendor\Package\Namespace\Class2;',
        'use Vendor\Package\Namespace\Class3;',
    ]);
});
