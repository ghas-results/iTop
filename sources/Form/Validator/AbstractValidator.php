<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

use utils;

/**
 * @since 3.1.0 N°6414 Field Validators refactoring
 */
abstract class AbstractValidator {
	const VALIDATOR_NAME = 'abstract';

	/** @var string Default message / dict key when an error occurs, if no custom one is specified in the constructor */
	public const DEFAULT_ERROR_MESSAGE = 'Core:Validator:Default';
	/** @var string message / dict key to use when an error occurs */
	protected string $sErrorMessage;

	public function __construct(?string $sErrorMessage) {
		if (false === utils::IsNullOrEmptyString($sErrorMessage)) {
			$this->sErrorMessage = $sErrorMessage;
		}
		else {
			$this->sErrorMessage = self::DEFAULT_ERROR_MESSAGE;
		}
	}

	/**
	 * @param mixed $value
	 *
	 * @return array<bool,?string> boolean valid for valid / invalid, and error message if invalid
	 */
	abstract public function Validate($value): array;

	/**
	 * Still used in \Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSelectObjectFieldRenderer::Render :(
	 *
	 * @return string
	 * @deprecated
	 */
	public static function GetName() {
		return static::VALIDATOR_NAME;
	}

	/**
	 * Still used in \Combodo\iTop\Renderer\Console\FieldRenderer\ConsoleSelectObjectFieldRenderer::Render :(
	 *
	 * @return string
	 * @deprecated
	 */
	public function GetErrorMessage() {
		return $this->sErrorMessage;
	}
}