<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * iTemporaryObjectFormValidator.
 *
 * Temporary object form validator interface.
 *
 * @since 3.1
 */
interface iTemporaryObjectFormValidator
{
	/**
	 * Validate.
	 *
	 * Validate temporary descriptors and return all valid.
	 *
	 * @param DBObject $oDbObject
	 * @param array $aTemporaryObjectDescriptors
	 *
	 * @return array
	 */
	public function Validate(DBObject $oDbObject, array $aTemporaryObjectDescriptors): array;

}