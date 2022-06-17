<?php

//use IBroStudio\FileDataManager\Facades\FileDataManager;

use IBroStudio\FileDataManager\FileDataManager;
use IBroStudio\FileDataManager\Managers\JsManager;
use IBroStudio\FileDataManager\Managers\JsonManager;
use IBroStudio\FileDataManager\Managers\PhpManager;
use Illuminate\Filesystem\Filesystem;

uses()->group('manager');

it('can instantiate a PhpManager', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'adapter');

    expect($PhpManager->getValue($FileDataManager))->toBeInstanceOf(PhpManager::class);
});

it('can instantiate a JsonManager', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/test.json');
    $JsonManager = getPrivateProperty($FileDataManager, 'adapter');

    expect($JsonManager->getValue($FileDataManager))->toBeInstanceOf(JsonManager::class);
});

it('can instantiate a JsManager', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/test.js');
    $JsonManager = getPrivateProperty($FileDataManager, 'adapter');

    expect($JsonManager->getValue($FileDataManager))->toBeInstanceOf(JsManager::class);
});

it('can find a value', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/test.json');
    $JsonManager = getPrivateProperty($FileDataManager, 'value');

    expect($JsonManager->getValue($FileDataManager))->toBe(null);

    $findValueCall = $FileDataManager->findValue('minimum-stability');

    expect($JsonManager->getValue($FileDataManager))->toBe('dev');
    expect($findValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can find an array value', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'value');

    expect($PhpManager->getValue($FileDataManager))->toBe(null);

    $findValueCall = $FileDataManager->findArray('$testArray4');

    expect($PhpManager->getValue($FileDataManager))->toMatchArray([
        'ArrayValue1::class',
        'ArrayValue2::class',
        'ArrayValue3::class',
    ]);
    expect($findValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can find a regex value', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'value');

    expect($PhpManager->getValue($FileDataManager))->toBe(null);

    $findValueCall = $FileDataManager->findRegex('#(use\s(.*?)\;)#s');

    expect($PhpManager->getValue($FileDataManager))->toMatchArray([
        'use Vendor\Package\Namespace\Class1;',
        'use Vendor\Package\Namespace\Class2;',
    ]);
    expect($findValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can replace a value', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'content');

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/Test.php')
    );

    $changeValueCall = $FileDataManager->replaceValue('$testValue', 'tata');

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/TestModifiedValue.php')
    );
    expect($changeValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can add a value to an array', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'content');

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/Test.php')
    );

    $changeValueCall = $FileDataManager->addArrayValue('$testArray1', "'NewValue'");

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/TestModifiedArray.php')
    );
    expect($changeValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can add a regex value', function () {
    $FileDataManager = FileDataManager::load(__DIR__ . '/Fixtures/Test.php');
    $PhpManager = getPrivateProperty($FileDataManager, 'content');

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/Test.php')
    );

    $changeValueCall = $FileDataManager->addRegexValue('#(use\s(.*?)\;)#s', 'use Vendor\Package\Namespace\Class3;');

    $this->assertEquals(
        $PhpManager->getValue($FileDataManager),
        file_get_contents(__DIR__ . '/Fixtures/TestModifiedRegex.php')
    );
    expect($changeValueCall)->toBeInstanceOf(FileDataManager::class);
});

it('can write a json file', function () {
    $filesystem = app(Filesystem::class);
    $filesystem->copy(__DIR__ . '/Fixtures/test.json', __DIR__ . '/Fixtures/testForWrite.json');
    FileDataManager::load(__DIR__ . '/Fixtures/testForWrite.json')
        ->replaceValue('name', 'tata')
        ->write();

    $this->assertEquals(
        file_get_contents(__DIR__ . '/Fixtures/testForWrite.json'),
        file_get_contents(__DIR__ . '/Fixtures/testWritten.json')
    );

    $filesystem->delete(__DIR__ . '/Fixtures/testForWrite.json');
});

it('can write a php file', function () {
    $filesystem = app(Filesystem::class);
    $filesystem->copy(__DIR__ . '/Fixtures/Test.php', __DIR__ . '/Fixtures/TestForWrite.php');
    FileDataManager::load(__DIR__ . '/Fixtures/TestForWrite.php')
        ->replaceValue('$testValue', 'tata')
        ->addArrayValue('$testArray2', "'NewValue'")
        ->addArrayValue('$testArray4', 'NewValue::class')
        ->addArrayValue('$testArray6', "'test2-styles'=>__DIR__.'/../dist/test2.css'")
        ->addRegexValue('#(use\s(.*?)\;)#s', 'use Vendor\Package\Namespace\Class3;')
        ->write();

    $this->assertEquals(
        file_get_contents(__DIR__ . '/Fixtures/TestForWrite.php'),
        file_get_contents(__DIR__ . '/Fixtures/TestWritten.php')
    );

    $filesystem->delete(__DIR__ . '/Fixtures/TestForWrite.php');
});

it('can write a js file', function () {
    $filesystem = app(Filesystem::class);
    $filesystem->copy(__DIR__ . '/Fixtures/test.js', __DIR__ . '/Fixtures/testForWrite.js');
    FileDataManager::load(__DIR__ . '/Fixtures/testForWrite.js')
        ->replaceValue('important', 'tata')
        ->addArrayValue('content', "'NewValue'")
        ->addArrayValue('plugins', "require('@tailwindcss/line-clamp')")
        ->addRegexValue('#(preflight\: (.*?)\,)#s', 'preflight: true,')
        ->write();

    $this->assertEquals(
        file_get_contents(__DIR__ . '/Fixtures/testForWrite.js'),
        file_get_contents(__DIR__ . '/Fixtures/TestWritten.js')
    );

    $filesystem->delete(__DIR__ . '/Fixtures/TestForWrite.js');
});
