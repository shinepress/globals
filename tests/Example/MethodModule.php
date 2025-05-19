<?php

/*
 * This file is part of ShinePress.
 *
 * (c) Shine United LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ShinePress\Globals\Tests\Example;

use ShinePress\Framework\Module;
use ShinePress\Globals\RegisterGlobal;
use ShinePress\Globals\Tests\Registration\MethodTest;

/**
 * @phpstan-import-type DataProviderArgs from MethodTest
 *
 * @implements DataProviderInterface<DataProviderArgs>
 */
class MethodModule extends Module implements DataProviderInterface {
	#[RegisterGlobal]
	public function toUppercase(string $input): string {
		return strtoupper($input);
	}

	#[RegisterGlobal]
	public function toLowercase(string $input): string {
		return strtolower($input);
	}

	#[RegisterGlobal('myFunctionName')]
	public function renamedFunction(): string {
		return 'renamed';
	}

	public static function dataProvider(): iterable {
		yield 'uppercase' => ['toUppercase', ['hElLo'], 'HELLO'];
		yield 'lowercase' => ['toLowercase', ['hElLo'], 'hello'];
		yield 'protected' => ['protectedFunction', [], 'protected'];
		yield 'private' => ['privateFunction', [], 'private'];
		yield 'renamed' => ['myFunctionName', [], 'renamed'];
	}

	#[RegisterGlobal]
	protected function protectedFunction(): string {
		return 'protected';
	}

	// @phpstan-ignore method.unused
	#[RegisterGlobal]
	private function privateFunction(): string {
		return 'private';
	}
}
