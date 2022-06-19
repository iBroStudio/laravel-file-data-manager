
# Laravel package to manage files data

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ibrostudio/laravel-file-data-manager.svg?style=flat-square)](https://packagist.org/packages/ibrostudio/laravel-file-data-manager)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ibrostudio/laravel-file-data-manager/run-tests?label=tests)](https://github.com/ibrostudio/laravel-file-data-manager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ibrostudio/laravel-file-data-manager/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ibrostudio/laravel-file-data-manager/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)


Little tool to read, change or add data in files.

Currently, it works with:
- .json files
- .php files
- .js files

## Installation

You can install the package via composer:

```bash
composer require ibrostudio/laravel-file-data-manager
```

## Usage
There is 3 types of chainable methods, callable in this order: load > manipulation > result
1. Load
- load(string $file)
2. Manipulation
- findValue(string $key)
- findArray(string $key)
- findRegex(string $regex)
- replaceValue(string $key, mixed $value)
- addArrayValue(string $key, mixed $value)
- addRegexValue(string $regex, string $value)
3. Result
- getContent()
- getValue()
- write()

#### Examples

- Working with values

```php
use IBroStudio\FileDataManager\FileDataManager;

$package_name = FileDataManager::load(base_path('composer.json'))
    ->findValue('name')
    ->getValue(); // = 'vendor/currentPackageName'

$package_name = FileDataManager::load(__DIR__ . '/Fixtures/Test.php')
    ->replaceValue('name', 'vendor/newPackageName')
    ->findValue('name')
    ->getValue(); // = 'vendor/newPackageName'
```

- Working with arrays

Test.php
```php
class Test
{
    protected array $testArray = [
        SomeClass1::class,
        SomeClass2::class,
    ];
}
```

```php
use IBroStudio\FileDataManager\FileDataManager;

$test = FileDataManager::load('Test.php');

$test
    ->findArray('$testArray')
    ->getValue(); // = ['SomeClass1::class', 'SomeClass2::class']

$test
    ->addArrayValue('$testArray', 'SomeClass3::class')
    ->write();

$test
    ->findArray('$testArray')
    ->getValue(); // = ['SomeClass1::class', 'SomeClass2::class', 'SomeClass3::class']
```

- Working with regex

Test.php
```php
use Vendor\Package\Namespace\Class1;

class Test{}
```

```php
use IBroStudio\FileDataManager\FileDataManager;

FileDataManager::load('Test.php')
    ->addRegexValue('#(use\s(.*?)\;)#s', 'use Vendor\Package\Namespace\Class2;')
    ->write();

$imports = FileDataManager::load('Test.php')
    ->findRegex('#(use\s(.*?)\;)#s')
    ->getValue(); // = ['use Vendor\Package\Namespace\Class1;', 'use Vendor\Package\Namespace\Class2;']
```

It is possible to chain manipulations methods:
```php
use IBroStudio\FileDataManager\FileDataManager;

FileDataManager::load('Test.php')
    ->replaceValue('$testValue', 'tata')
    ->addArrayValue('$testArray1', "'NewValue'")
    ->addArrayValue('$testArray2', 'OtherNewValue::class')
    ->addRegexValue('#(use\s(.*?)\;)#s', 'use Vendor\Package\Namespace\Class2;')
    ->write();
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [iBroStudio](https://github.com/iBroStudio)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
