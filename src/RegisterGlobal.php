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

namespace ShinePress\Globals;

use Attribute;
use Closure;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use ShinePress\Framework\Attribute\ConstantAttributeInterface;
use ShinePress\Framework\Attribute\MethodAttributeInterface;
use ShinePress\Framework\Attribute\ObjectAttributeInterface;
use ShinePress\Framework\Attribute\PropertyAttributeInterface;
use ShinePress\Framework\Module;
use ShinePress\Globals\Exception\AlreadyDefinedException;
use ShinePress\Globals\Exception\InvalidIdentifierException;
use ValueError;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class RegisterGlobal implements ConstantAttributeInterface, MethodAttributeInterface, ObjectAttributeInterface, PropertyAttributeInterface {
	private ?string $identifier;

	/**
	 * @throws InvalidIdentifierException if specified identifier is invalid
	 */
	public function __construct(?string $identifier = null) {
		if (!is_null($identifier)) {
			$this->validateIdentifier($identifier);
		}

		$this->identifier = $identifier;
	}

	/**
	 * @throws AlreadyDefinedException if specified global is already defined
	 */
	public function register(Module $module, ReflectionClassConstant|ReflectionObject|ReflectionMethod|ReflectionProperty $reflector): void {
		if ($reflector instanceof ReflectionClassConstant) {
			$this->registerConstant($module, $reflector);
		}

		if ($reflector instanceof ReflectionObject) {
			$this->registerObject($module, $reflector);
		}

		if ($reflector instanceof ReflectionMethod) {
			$this->registerMethod($module, $reflector);
		}

		if ($reflector instanceof ReflectionProperty) {
			$this->registerProperty($module, $reflector);
		}
	}

	/**
	 * @throws InvalidIdentifierException
	 */
	private function validateIdentifier(string $identifier): void {
		$pattern = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/';
		if (!preg_match($pattern, $identifier)) {
			throw new InvalidIdentifierException(sprintf(
				'Invalid global identifier "%s". Identifiers must start with a letter or underscore, followed by any number of letters, numbers, or underscores.',
				$identifier,
			));
		}
	}

	/**
	 * @throws AlreadyDefinedException
	 */
	private function registerConstant(Module $module, ReflectionClassConstant $constant): void {
		$identifier = $this->identifier ?? $constant->getName();

		if (defined($identifier)) {
			throw new AlreadyDefinedException(vsprintf(
				'Global constant "%s" already exists, unable to assign module constant %s::%s.',
				[
					$identifier,
					$module::class,
					$constant->getName(),
				],
			));
		}

		define(
			$identifier,
			$constant->getValue(),
		);
	}

	/**
	 * @throws AlreadyDefinedException
	 */
	private function registerObject(Module $module, ReflectionObject $object): void {
		$identifier = $this->identifier ?? $object->getShortName();

		if (isset($GLOBALS[$identifier])) {
			throw new AlreadyDefinedException(vsprintf(
				'Global variable "%s" already exists, unable to assign module instance %s.',
				[
					$identifier,
					$module::class,
				],
			));
		}

		$GLOBALS[$identifier] = $module;
	}

	/**
	 * @throws AlreadyDefinedException
	 */
	private function registerMethod(Module $module, ReflectionMethod $method): void {
		$identifier = $this->identifier ?? $method->getName();

		if (function_exists($identifier)) {
			throw new AlreadyDefinedException(sprintf(
				'Global function "%s" already exists, unable to assign module method %s%s%s().',
				$identifier,
				$module::class,
				$method->isStatic() ? '::' : '->',
				$method->getName(),
			));
		}

		// note: this method requires the use of eval and thus shares the same weaknesses as eval

		$class = uniqid('global_');

		$classCode = implode('', [
			'class ' . $class . ' {',
			'public static Closure $closure;',
			'}',
		]);

		$functionCode = implode('', [
			'function ' . $identifier . '(mixed ...$args): mixed {',
			'return call_user_func_array(' . $class . '::$closure, $args);',
			'}',
		]);

		try {
			if ($method->isStatic()) {
				$closure = $method->getClosure();
			} else {
				$closure = $method->getClosure($module);
			}

			eval(implode("\n", [
				$classCode,
				$functionCode,
			]));

			$class::$closure = $closure;
		} catch (ValueError $error) {
			// shouldn't happen, just don't do anything
		} catch (ReflectionException $exception) {
			// shouldn't happen, just don't do anything
		}
	}

	/**
	 * @throws AlreadyDefinedException
	 */
	private function registerProperty(Module $module, ReflectionProperty $property): void {
		$identifier = $this->identifier ?? $property->getName();

		if (isset($GLOBALS[$identifier])) {
			throw new AlreadyDefinedException(vsprintf(
				'Global variable "%s" already exists, unable to assign module property %s%s%s.',
				[
					$identifier,
					$module::class,
					$property->isStatic() ? '::$' : '->',
					$property->getName(),
				],
			));
		}

		$mapProperty = Closure::bind(function(string $global, string $property): void {
			// map a reference to the internal property into $GLOBALS
			$GLOBALS[$global] = &$this->{$property};
		}, $module, $module::class);

		$mapProperty(
			$identifier,
			$property->getName(),
		);
	}
}
