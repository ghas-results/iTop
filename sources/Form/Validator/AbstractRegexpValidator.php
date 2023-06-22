<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

abstract class AbstractRegexpValidator extends AbstractValidator {
	public const VALIDATOR_NAME = 'abstract_regexp';

	/** @var string Override in children classes to set regexp to use for validation */
	public const DEFAULT_REGEXP = '';

	protected string $sRegExp;

	public function __construct(?string $sErrorMessage = null) {
		$this->sRegExp = static::DEFAULT_REGEXP;
		parent::__construct($sErrorMessage);
	}

	public function Validate($value): array {
		if (preg_match($this->GetRegExp(true), $value)) {
			return [true, null];
		}

		return [false, $this->sErrorMessage];
	}

	/**
	 * Returns the regular expression of the validator.
	 *
	 * @param boolean $bWithSlashes If true, surrounds $sRegExp with '/'. Used with preg_match & co
	 *
	 * @return string
	 */
	public function GetRegExp($bWithSlashes = false) {
		if ($bWithSlashes) {
			$sRet = '/'.str_replace('/', '\\/', $this->sRegExp).'/';
		}
		else {
			$sRet = $this->sRegExp;
		}

		return $sRet;
	}
}