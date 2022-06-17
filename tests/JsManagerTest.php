<?php

//use IBroStudio\FileDataManager\Facades\FileDataManager;

use IBroStudio\FileDataManager\FileDataManager;

uses()->group('js');

it('can load a js file', function () {
    $loaded = FileDataManager::load(__DIR__ . '/Fixtures/test.js');

    $this->assertEquals(
        $loaded->getContent(),
        file_get_contents(__DIR__ . '/Fixtures/test.js')
    );
});

it('can find a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->findValue('important')
        ->getValue();

    expect($value)->toBe('.{{ plugin_name }}');
});

it('can find an array value', function ($key, $expectedValue) {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->findArray($key)
        ->getValue();

    expect($value)->toBe($expectedValue);
})->with([
    ['content', ["'./resources/views/**/*.blade.php'"]],
    ['plugins', ["require('@tailwindcss/forms')", "require('@tailwindcss/typography')"]]
]);

it('can find a regex value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->findRegex('#(preflight\: false)#s')
        ->getValue();

    expect($value)->toMatchArray([
        'preflight: false',
    ]);
});

it('can replace a value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->replaceValue('important', 'tata')
        ->findValue('important')
        ->getValue();

    expect($value)->toBe('tata');
});

it('can add a value to an array', function ($key, $newValue, $expectedValue) {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->addArrayValue($key, $newValue)
        ->findArray($key)
        ->getValue();

    expect($value)->toBe($expectedValue);
})->with([
    ['content', "'NewValue'", ["'./resources/views/**/*.blade.php'", "'NewValue'"]],
    ['plugins', "require('@tailwindcss/line-clamp')", ["require('@tailwindcss/forms')", "require('@tailwindcss/typography')", "require('@tailwindcss/line-clamp')"]]
]);

it('can add a regex value', function () {
    $value = FileDataManager::load(__DIR__ . '/Fixtures/test.js')
        ->addRegexValue('#(preflight\: (.*?)\,)#s', 'preflight: true,')
        ->findRegex('#(preflight\: (.*?)\,)#s')
        ->getValue();

    expect($value)->toMatchArray([
        'preflight: false,',
        'preflight: true,',
    ]);
});