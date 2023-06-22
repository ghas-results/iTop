<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

use utils;

class MultipleChoicesValidator extends AbstractValidator {
	/** @var array List of possible choices */
	private array $aChoices;

	public function __construct(array $aChoices) {
		parent::__construct();
		$this->aChoices = $aChoices;
	}

	public function Validate($value): array {
		if (count($value) === 0) {
			return [];
		}

		$aErrorMessages = [];
		foreach ($value as $sCode => $valueItem) {
			if (utils::IsNullOrEmptyString($valueItem)) {
				continue;
			}
			if (false === array_key_exists($valueItem, $this->aChoices)) {
				$aErrorMessages[] = "Value ({$valueItem}) is not part of the field possible values list";
			}
		}

		return $aErrorMessages;
	}
}