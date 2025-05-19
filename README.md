# shinepress/globals

[![License](https://img.shields.io/packagist/l/shinepress/globals)](https://github.com/shinepress/globals/blob/main/LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/shinepress/globals?label=latest)](https://packagist.org/packages/shinepress/globals/)
[![PHP Version](https://img.shields.io/packagist/dependency-v/shinepress/globals/php?label=php)](https://www.php.net/releases/index.php)
[![Main Status](https://img.shields.io/github/actions/workflow/status/shinepress/globals/verify.yml?branch=main&label=main)](https://github.com/shinepress/globals/actions/workflows/verify.yml?query=branch%3Amain)
[![Release Status](https://img.shields.io/github/actions/workflow/status/shinepress/globals/verify.yml?branch=release&label=release)](https://github.com/shinepress/globals/actions/workflows/verify.yml?query=branch%3Arelease)
[![Develop Status](https://img.shields.io/github/actions/workflow/status/shinepress/globals/verify.yml?branch=develop&label=develop)](https://github.com/shinepress/globals/actions/workflows/verify.yml?query=branch%3Adevelop)


## Description

Add-on for [shinepress/framework](https://packagist.org/packages/shinepress/framework/) to allow registering of global variables, functions, and constants for use outside of modules.


## Installation

The recommendend installation method is with composer:
```sh
$ composer require shinepress/globals
```


## Usage

Add the 'RegisterGlobal' attribute to any module class/property/constant/method to register it into the global namespace.


### Module Instance

```php
use ShinePress\Framework\Module;
use ShinePress\Globals\RegisterGlobal;

#[RegisterGlobal('module')] // registers as $module
#[RegisterGlobal] // registers as $ExampleModule
class ExampleModule extends Module {

	public function hello(): string {
		return 'world';
	}
}

ExampleModule::register();
```

```php
global $module;
print $module->hello();
// prints: 'world'
```

```php
global $ExampleModule;
print $ExampleModule->hell();
// prints: 'world'
```


### Module Property

Assigns a reference to the indicated class property to the global variable regardless of visibility.

```php
use ShinePress\Framework\Module;
use ShinePress\Globals\RegisterGlobal;

class ExampleModule extends Module {

	#[RegisterGlobal('testProperty')]
	public string $myProperty = 'foo';
}

ExampleModule::register();
```

```php
global $testProperty;
print $testProperty;
// prints: 'foo';

$testProperty = 'bar';
print ExampleModule::instance()->myProperty;
// prints: 'bar'
```


### Module Constant

Defines a global constant with the value of the class constant regardless of visibility.

```php
use ShinePress\Framework\Module;
use ShinePress\Globals\RegisterGlobal;

class ExampleModule extends Module {

	#[RegisterGlobal('TEST_CONSTANT')]
	public const MY_CONSTANT = 'foobar';
}

ExampleModule::register();
```

```php
print TEST_CONSTANT;
// prints: 'foobar'
```


### Module Method

Creates a closure reference to the indicated method and assigns it to a function in the global scope.

Note: the relies on eval, with all the associated downsides.

```php
use ShinePress\Framework\Module;
use ShinePress\Globals\RegisterGlobal;

class ExampleModule extends Module {

	#[RegisterGlobal('toLowercase')]
	public function lowercase(string $input): string {
		return strtolower($input);
	}

	#[RegisterGlobal('toUppercase')]
	public function uppercase(string $input): string {
		return strtoupper($input);
	}
}

ExampleModule::register();
```

```php
print toLowercase('HELLOWORLD');
// prints: 'helloworld'

print toUppercase('helloworld');
// prints: 'HELLOWORLD'
```