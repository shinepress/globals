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
use ShinePress\Globals\Tests\Example\ObjectModule;

/**
 * @phpstan-type GlobalName string
 * @phpstan-type DataProviderArgs array{0: GlobalName}
 */
class ObjectTest extends TestCase {
	#[DataProviderExternal(ObjectModule::class, 'dataProvider')]
	public function testObjects(string $name): void {
		self::assertFalse(isset($GLOBALS[$name]), sprintf(
			'variable "%s" already exists',
			$name,
		));

		ObjectModule::register();

		self::assertTrue(isset($GLOBALS[$name]), sprintf(
			'variable "%s" does not exist',
			$name,
		));

		self::assertInstanceOf(ObjectModule::class, $GLOBALS[$name], sprintf(
			'variable "%s" is not a valid instanceof of "%s"',
			$name,
			ObjectModule::class,
		));

		self::assertSame(ObjectModule::instance(), $GLOBALS[$name], sprintf(
			'variable "%s" is a different instance of "%s"',
			$name,
			ObjectModule::class,
		));
	}
}
