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

namespace ShinePress\Globals\Tests\Registration;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use ShinePress\Globals\Tests\Example\MethodModule;

/**
 * @phpstan-type FunctionName string
 * @phpstan-type Arguments array<int, mixed>
 * @phpstan-type ExpectedReturn mixed
 * @phpstan-type DataProviderArgs array{0: FunctionName, 1: Arguments, 2: ExpectedReturn}
 */
class MethodTest extends TestCase {
	/**
	 * @param FunctionName   $name
	 * @param Arguments      $args
	 * @param ExpectedReturn $expected
	 */
	#[DataProviderExternal(MethodModule::class, 'dataProvider')]
	public function testMethods(string $name, array $args, mixed $expected): void {
		self::assertFalse(function_exists($name), sprintf(
			'function "%s" already exists',
			$name,
		));

		MethodModule::register();

		self::assertTrue(function_exists($name), sprintf(
			'function "%s" does not exist',
			$name,
		));

		$result = call_user_func_array($name, $args);
		self::assertSame($expected, $result, sprintf(
			'function "%s" did not return expected result',
			$name,
		));
	}
}
