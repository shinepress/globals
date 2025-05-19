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
use ShinePress\Globals\Tests\Registration\ObjectTest;

/**
 * @phpstan-import-type DataProviderArgs from ObjectTest
 *
 * @implements DataProviderInterface<DataProviderArgs>
 */
#[RegisterGlobal]
#[RegisterGlobal('alternate1')]
#[RegisterGlobal('alternate2')]
class ObjectModule extends Module implements DataProviderInterface {
	public static function dataProvider(): iterable {
		yield 'default' => [
			'ObjectModule',
		];

		yield 'alt1' => [
			'alternate1',
		];

		yield 'alt2' => [
			'alternate2',
		];
	}
}
