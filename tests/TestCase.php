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

namespace ShinePress\Globals\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	protected static function toDo(): void {
		$caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

		$function = $caller['function'];
		if (isset($caller['class'])) {
			$function = $caller['class'] . '::' . $function;
		}

		$message = 'To-Do: ' . $function;

		self::markTestIncomplete($function);
	}
}
