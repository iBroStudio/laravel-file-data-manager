<?php

//use IBroStudio\FileDataManager\Facades\FileDataManager;

use IBroStudio\FileDataManager\FileDataManager;

uses()->group('json');

it('can load a json file', function () {
    $loaded = FileDataManager::load(__DIR__ . '/Fixtures/test.json');

    $this->assertEquals(
        $loaded->getContent(),
        file_get_contents(__DIR__ . '/Fixtures/test.json')
    );
});

it('can find a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->findValue('minimum-stability')
        ->getValue();

    expect($value)->toBe('dev');
});

it('can find an array value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->findValue('require')
        ->getValue();

    expect($value)->toMatchArray([
        'laravel/framework' => '~5.0',
    ]);
});

it('can find an array value with a dotted key', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->findValue('autoload.classmap')
        ->getValue();

    expect($value)->toMatchArray([
        'database',
        'tests/TestCase.php',
    ]);
});

it('can replace a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->replaceValue('name', 'tata')
        ->findValue('name')
        ->getValue();

    expect($value)->toBe('tata');
});

it('can add a value to an array', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->addArrayValue('keywords', 'tata')
        ->findValue('keywords')
        ->getValue();

    expect($value)->toMatchArray(['framework', 'laravel', 'tata']);
});

it('can add a value to an array with a dotted key', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.json')
        ->addArrayValue('extra.laravel.dont-discover', 'tata')
        ->findValue('extra.laravel.dont-discover')
        ->getValue();

    expect($value)->toMatchArray(['tata']);
});

/*
"keywords": ["framework", "laravel"],

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
        'use Vendor\Package\Namespace\Class3;'
    ]);
});



addArrayValue(string $key, mixed $value)

it('can load a file', function () {

$file = FileDataManager::load(base_path('test.php'))
    ->findRegex('#use\s(.*?)\;#s');
    dd($file);

$file = FileDataManager::load(base_path('test.php'))
    ->findArray('$relationManagers');
    dd($file);

$file = FileDataManager::load(base_path('test.php'))
    ->findValue('$test');
    dd($file);

$file = FileDataManager::load(base_path('test.php'))
    ->addProperties('toto', 'use\s(.*?)\;');
    dd($file);

$file = FileDataManager::load(base_path('test.php'))
    ->addProperty('toto', '$resources');
    dd($file);

    $file = FileDataManager::load(base_path('test.php'))
    ->findProperty('$resources');
    dd($file);

$file = FileDataManager::load(base_path('composer.json'))
    ->addProperty(['toto'], 'extra.laravel.alias');
    dd($file);

$file = FileDataManager::load(base_path('composer.json'))
    ->addProperty('toto', 'extra.laravel.dont-discover');
    dd($file);

    $file = FileDataManager::load(base_path('composer.json'))
    ->addProperty('toto', 'minimum-stability');
    dd($file);

    $file = FileDataManager::load(base_path('composer.json'))
    ->addProperty('toto', 'autoload.classmap');
    dd($file);
});


- add use Class; => array of Use ... importClass
- add property to array findProperty
- npm json array workspaces findProperty


*/
