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

use Error;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use ShinePress\Globals\Tests\Example\ConstantModule;

/**
 * @phpstan-type GlobalName string
 * @phpstan-type ExpectedValue scalar|array<scalar>
 * @phpstan-type DataProviderArgs array{0: GlobalName, 1: ExpectedValue}
 */
class ConstantTest extends TestCase {
	public function testModule(): void {
		global $constants;

		self::assertNull($constants);

		ConstantModule::register();

		self::assertInstanceOf(ConstantModule::class, $constants);
		self::assertSame(ConstantModule::instance(), $constants);
	}

	#[DataProviderExternal(ConstantModule::class, 'dataProvider')]
	public function testConstants(string $name, mixed $expected): void {
		self::assertFalse(defined($name), sprintf(
			'constant "%s" is already defined',
			$name,
		));

		ConstantModule::register();

		self::assertTrue(defined($name), sprintf(
			'constant "%s" is not defined',
			$name,
		));

		try {
			$actual = constant($name);
		} catch (Error $error) {
			$actual = null;
		}

		self::assertSame($expected, $actual, sprintf(
			'constant "%s" value does not match expected',
			$name,
		));
	}
}
