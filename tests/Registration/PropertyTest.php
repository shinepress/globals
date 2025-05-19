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
use ShinePress\Globals\Tests\Example\PropertyModule;

/**
 * @phpstan-type GlobalName string
 * @phpstan-type InitialValue string
 * @phpstan-type TargetValue string
 * @phpstan-type DataProviderArgs array{0: GlobalName, 1: InitialValue, 2: TargetValue}
 */
class PropertyTest extends TestCase {
	#[DataProviderExternal(PropertyModule::class, 'dataProvider')]
	public function testProperties(string $name, mixed $initial, mixed $target): void {
		self::assertFalse(isset($GLOBALS[$name]));

		PropertyModule::register();

		self::assertTrue(isset($GLOBALS[$name]));

		self::assertSame($initial, $GLOBALS[$name]);

		$GLOBALS[$name] = $target;

		self::assertSame($target, $GLOBALS[$name]);

		$instance = PropertyModule::instance();
		self::assertSame($target, $instance->value($name));
	}
}
