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
use ShinePress\Globals\Tests\Registration\PropertyTest;

/**
 * @phpstan-import-type DataProviderArgs from PropertyTest
 *
 * @implements DataProviderInterface<DataProviderArgs>
 */
class PropertyModule extends Module implements DataProviderInterface {
	#[RegisterGlobal]
	public string $publicString = 'public';

	#[RegisterGlobal]
	protected string $protectedString = 'protected';

	public function value(string $name): mixed {
		switch ($name) {
			case 'publicString': return $this->publicString;
			case 'protectedString': return $this->protectedString;
		}

		return null;
	}

	public static function dataProvider(): iterable {
		yield 'public' => [
			'publicString',
			'public',
			'helloworld',
		];

		yield 'protected' => [
			'protectedString',
			'protected',
			'foobar',
		];
	}
}
