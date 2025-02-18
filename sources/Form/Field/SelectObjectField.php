<?php

/**
 * Copyright (C) 2013-2023 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Form\Field;

use BinaryExpression;
use Closure;
use Combodo\iTop\Form\Validator\NotEmptyExtKeyValidator;
use ContextTag;
use DBObjectSet;
use DBSearch;
use FieldExpression;
use MetaModel;
use ScalarExpression;
use utils;

/**
 * Description of SelectObjectField
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 * @since 2.3.0
 */
class SelectObjectField extends Field
{
	/** @var int CONTROL_SELECT */
	const CONTROL_SELECT = 1;
	/** @var int CONTROL_RADIO_VERTICAL */
	const CONTROL_RADIO_VERTICAL = 2;

	/** @var \DBSearch $oSearch */
	protected $oSearch;
	/**
	 * @see \Config 'max_combo_length'
	 * @var int $iMaximumComboLength
	 */
	protected $iMaximumComboLength;
	/**
	 * @see \Config 'min_autocomplete_chars'
	 * @var int $iMinAutoCompleteChars
	 */
	protected $iMinAutoCompleteChars;
	/**
	 * @see \Config 'max_autocomplete_results'
	 * @var int
	 * @since 3.0.0
	 */
	protected $iMaxAutoCompleteResults;
	/** @var bool $bHierarchical */
	protected $bHierarchical;
	/** @var int $iControlType */
	protected $iControlType;
	/** @var string $sSearchEndpoint */
	protected $sSearchEndpoint;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->oSearch = null;
		$this->iMaximumComboLength = MetaModel::GetConfig()->Get('max_combo_length');
		$this->iMinAutoCompleteChars = MetaModel::GetConfig()->Get('min_autocomplete_chars');
		$this->iMaxAutoCompleteResults = MetaModel::GetConfig()->Get('max_autocomplete_results');
		$this->bHierarchical = false;
		$this->iControlType = self::CONTROL_SELECT;
		$this->sSearchEndpoint = null;
	}

	/**
	 * @param \DBSearch $oSearch
	 *
	 * @return $this
	 */
	public function SetSearch(DBSearch $oSearch)
	{
		$this->oSearch = $oSearch;

		return $this;
	}

	/**
	 * @param int $iMaximumComboLength
	 *
	 * @return $this
	 */
	public function SetMaximumComboLength(int $iMaximumComboLength)
	{
		$this->iMaximumComboLength = $iMaximumComboLength;

		return $this;
	}

	/**
	 * @param int $iMinAutoCompleteChars
	 *
	 * @return $this
	 */
	public function SetMinAutoCompleteChars(int $iMinAutoCompleteChars)
	{
		$this->iMinAutoCompleteChars = $iMinAutoCompleteChars;

		return $this;
	}

	/**
	 * @see static::$iMaxAutoCompleteResults
	 *
	 * @param int $iMaxAutoCompleteResults
	 *
	 * @return $this;
	 * @since 3.0.0
	 */
	public function SetMaxAutoCompleteResults(int $iMaxAutoCompleteResults)
	{
		$this->iMaxAutoCompleteResults = $iMaxAutoCompleteResults;

		return $this;
	}

	/**
	 * @param bool $bHierarchical
	 *
	 * @return $this
	 */
	public function SetHierarchical(bool $bHierarchical)
	{
		$this->bHierarchical = $bHierarchical;

		return $this;
	}

	/**
	 * @param int $iControlType
	 */
	public function SetControlType(int $iControlType)
	{
		$this->iControlType = $iControlType;
	}

	/**
	 * @param string $sSearchEndpoint
	 *
	 * @return $this
	 */
	public function SetSearchEndpoint(string $sSearchEndpoint)
	{
		$this->sSearchEndpoint = $sSearchEndpoint;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function SetMandatory(bool $bMandatory)
	{
		// Before changing the property, we check if it was already mandatory. If not, we had the mandatory validator
		if ($bMandatory && !$this->bMandatory)
		{
			$this->AddValidator(new NotEmptyExtKeyValidator());
		}

		if (!$bMandatory)
		{
			foreach ($this->aValidators as $iKey => $oValue)
			{
				if ($oValue::Getname() === NotEmptyExtKeyValidator::GetName())
				{
					unset($this->aValidators[$iKey]);
				}
			}
		}

		$this->bMandatory = $bMandatory;

		return $this;
	}

	/**
	 * @return \DBSearch
	 */
	public function GetSearch()
	{
		return $this->oSearch;
	}

	/**
	 * @return int|null
	 */
	public function GetMaximumComboLength()
	{
		return $this->iMaximumComboLength;
	}

	/**
	 * @return int
	 */
	public function GetMinAutoCompleteChars()
	{
		return $this->iMinAutoCompleteChars;
	}

	/**
	 * @see static::$iMaxAutoCompleteResults
	 * @return int
	 * @since 3.0.0
	 */
	public function GetMaxAutoCompleteResults(): int
	{
		return $this->iMaxAutoCompleteResults;
	}

	/**
	 * @return bool
	 */
	public function GetHierarchical()
	{
		return $this->bHierarchical;
	}

	/**
	 * @return int
	 */
	public function GetControlType() {
		return $this->iControlType;
	}

	/**
	 * @return string|null
	 */
	public function GetSearchEndpoint() {
		return $this->sSearchEndpoint;
	}

	public function Validate() {
		if ((ContextTag::Check(ContextTag::TAG_REST)) && ($this->GetReadOnly() === false)) {
			// Only doing the check when coming from the REST API, as the user portal might send invalid values (see VerifyCurrentValue() method below)
			// Also do not check read only fields, are they are send with a null value when submitting request template from the console
			$sCurrentValueForExtKey = $this->currentValue;
			if (utils::IsNotNullOrEmptyString($sCurrentValueForExtKey) && ($sCurrentValueForExtKey !== 0)) {
				$oSetForExistingCurrentValue = $this->GetObjectsSet();
				$iObjectsCount = $oSetForExistingCurrentValue->CountWithLimit(1);

				if ($iObjectsCount === 0) {
					$this->SetValid(false);
					$this->AddErrorMessage("Value $sCurrentValueForExtKey does not match the corresponding filter set");

					return $this->GetValid();
				}
			}
		}

		return parent::Validate();
	}

	/**
	 * Resets current value if not among allowed ones.
	 * By default, reset is done ONLY when the field is not read-only.
	 *
	 * Called conditionally from {@see \Combodo\iTop\Portal\Form\ObjectFormManager::Build}
	 * This check isn't in the Validate method as we don't want to check for untouched and invalid values (value was set in the past, it is now invalid, but the user didn't change it)
	 *
	 * @param boolean $bAlways Set to true to verify even when the field is read-only.
	 *
	 * @throws \CoreException
	 */
	public function VerifyCurrentValue(bool $bAlways = false) {
		if (!$this->GetReadOnly() || $bAlways) {
			$oValuesSet = $this->GetObjectsSet();

			if ($oValuesSet->Count() === 0) {
				$this->currentValue = null;
			}
		}
	}

	final protected function GetObjectsSet() {
		$sCurrentValueForExtKey = $this->currentValue;

		$oSearchForExistingCurrentValue = $this->oSearch->DeepClone();
		$oCheckIdAgainstCurrentValueExpression = new BinaryExpression(
			new FieldExpression('id', $oSearchForExistingCurrentValue->GetClassAlias()),
			'=',
			new ScalarExpression($sCurrentValueForExtKey)
		);
		$oSearchForExistingCurrentValue->AddConditionExpression($oCheckIdAgainstCurrentValueExpression);

		return new DBObjectSet($oSearchForExistingCurrentValue);
	}
}
