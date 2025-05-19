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
use ShinePress\Globals\Tests\Registration\ConstantTest;

/**
 * @phpstan-import-type DataProviderArgs from ConstantTest
 *
 * @implements DataProviderInterface<DataProviderArgs>
 */
#[RegisterGlobal('constants')]
class ConstantModule extends Module implements DataProviderInterface {
	#[RegisterGlobal('RENAMED')]
	public const TEST_RENAMED = 'renamed';

	#[RegisterGlobal]
	public const TEST_BOOL = true;

	#[RegisterGlobal]
	public const TEST_INT = 1;

	#[RegisterGlobal]
	public const TEST_FLOAT = 1.01;

	#[RegisterGlobal]
	public const TEST_STRING = 'one';

	#[RegisterGlobal]
	public const TEST_ARRAY = [self::TEST_BOOL, self::TEST_INT, self::TEST_FLOAT, self::TEST_STRING];

	#[RegisterGlobal]
	protected const TEST_PROTECTED = 'protected';

	#[RegisterGlobal]
	private const TEST_PRIVATE = 'private'; // @phpstan-ignore classConstant.unused

	public static function dataProvider(): iterable {
		yield 'renamed' => ['RENAMED', 'renamed'];
		yield 'private' => ['TEST_PRIVATE', 'private'];
		yield 'protected' => ['TEST_PROTECTED', 'protected'];
		yield 'bool' => ['TEST_BOOL', true];
		yield 'int' => ['TEST_INT', 1];
		yield 'float' => ['TEST_FLOAT', 1.01];
		yield 'string' => ['TEST_STRING', 'one'];
		yield 'array' => ['TEST_ARRAY', [
			true,
			1,
			1.01,
			'one',
		]];
	}
}
