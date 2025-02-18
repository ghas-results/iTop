<?php

// Copyright (C) 2010-2023 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use Closure;
use ContextTag;
use utils;

/**
 * Description of MultipleChoicesField
 *
 * Choices = Set of items that can be picked
 * Values = Items that have been picked
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.3.0
 */
abstract class MultipleChoicesField extends Field
{
	/** @var bool DEFAULT_MULTIPLE_VALUES_ENABLED */
	const DEFAULT_MULTIPLE_VALUES_ENABLED = false;

	/** @var bool $bMultipleValuesEnabled */
	protected $bMultipleValuesEnabled;
	/** @var array $aChoices */
	protected $aChoices;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->bMultipleValuesEnabled = static::DEFAULT_MULTIPLE_VALUES_ENABLED;
		$this->aChoices = array();
		$this->currentValue = array();
	}

	/**
	 * @inheritDoc
	 */
	public function GetCurrentValue()
	{
		$value = null;
		if (!empty($this->currentValue))
		{
			if ($this->bMultipleValuesEnabled)
			{
				$value = $this->currentValue;
			}
			else
			{
				reset($this->currentValue);
				$value = current($this->currentValue);
			}
		}

		return $value;
	}

	/**
	 * Sets the current value for the MultipleChoicesField.
	 *
	 * @param mixed $currentValue Can be either an array of values (in case of multiple values) or just a simple value
	 * @return $this
	 */
	public function SetCurrentValue($currentValue)
	{
		if (is_array($currentValue))
		{
			$this->currentValue = $currentValue;
		}
		elseif (is_null($currentValue))
		{
			$this->currentValue = array();
		}
		else
		{
			$this->currentValue = array($currentValue);
		}
		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetMultipleValuesEnabled()
	{
		return $this->bMultipleValuesEnabled;
	}

	/**
	 * @param bool $bMultipleValuesEnabled
	 *
	 * @return $this
	 */
	public function SetMultipleValuesEnabled(bool $bMultipleValuesEnabled)
	{
		$this->bMultipleValuesEnabled = $bMultipleValuesEnabled;
		return $this;
	}

	/**
	 * @param array $aValues
	 *
	 * @return $this
	 */
	public function SetValues(array $aValues)
	{
		$this->currentValue = $aValues;
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function AddValue($value)
	{
		$this->currentValue = $value;
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function RemoveValue($value)
	{
		if (array_key_exists($value, $this->currentValue))
		{
			unset($this->currentValue[$value]);
		}
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function IsAmongValues($value)
	{
		return in_array($value, $this->currentValue);
	}

	/**
	 * @return array
	 */
	public function GetChoices()
	{
		return $this->aChoices;
	}

	/**
	 * @param array $aChoices
	 *
	 * @return $this
	 */
	public function SetChoices(array $aChoices)
	{
		$this->aChoices = $aChoices;
		return $this;
	}

	/**
	 * @param string $sId
	 * @param null   $choice
	 *
	 * @return $this
	 */
	public function AddChoice(string $sId, $choice = null)
	{
		if ($choice === null)
		{
			$choice = $sId;
		}
		$this->aChoices[$sId] = $choice;
		return $this;
	}

	/**
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function RemoveChoice(string $sId)
	{
		if (in_array($sId, $this->aChoices))
		{
			unset($this->aChoices[$sId]);
		}
		return $this;
	}

	public function Validate() {
		$this->SetValid(true);
		$this->EmptyErrorMessages();

		if ((ContextTag::Check(ContextTag::TAG_REST)) && ($this->GetReadOnly() === false)) {
			// Only doing the check when coming from the REST API, as the user portal might send invalid values (see VerifyCurrentValue() method below)
			// Also do not check read only fields, are they are send with a null value when submitting request template from the console
			if (count($this->currentValue) > 0) {
				foreach ($this->currentValue as $sCode => $value) {
					if (utils::IsNullOrEmptyString($value)) {
						continue;
					}
					if (false === array_key_exists($value, $this->aChoices)) {
						$this->SetValid(false);
						$this->AddErrorMessage("Value ({$value}) is not part of the field possible values list");
					}
				}
			}
		}

		foreach ($this->GetValidators() as $oValidator) {
			foreach ($this->currentValue as $value) {
				if (!preg_match($oValidator->GetRegExp(true), $value)) {
					$this->SetValid(false);
					$this->AddErrorMessage($oValidator->GetErrorMessage());
				}
			}
		}

		return $this->GetValid();
	}

}
