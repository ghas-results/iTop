<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use AttributeExternalKey;
use DBObject;
use MetaModel;

/**
 * TemporaryObjectExtKeyValidator.
 *
 * Temporary object validators for objects created via external keys.
 *
 * @since 3.1
 */
class TemporaryObjectExtKeyValidator
{

	/**
	 * Validate.
	 *
	 * Remove target object from the temporary object descriptors.
	 * Process target object to handle child elements.
	 *
	 * @param DBObject $oDbObject
	 * @param array $aTemporaryObjectDescriptors
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function Validate(DBObject $oDbObject, array $aTemporaryObjectDescriptors): array
	{
		$aValidTemporaryObjectDescriptors = [];

		$this->ProcessObjectExternalKeys($oDbObject, $aTemporaryObjectDescriptors, $aValidTemporaryObjectDescriptors);

		return $aValidTemporaryObjectDescriptors;
	}

	/**
	 * ProcessObject.
	 *
	 * @param DBObject $oDbObject
	 * @param array $aTemporaryObjectDescriptors
	 * @param array $aValidTemporaryObjectDescriptors
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	private function ProcessObjectExternalKeys(DBObject $oDbObject, array $aTemporaryObjectDescriptors, array &$aValidTemporaryObjectDescriptors)
	{
		// Retrieve object attributes
		$aAttDefs = MetaModel::ListAttributeDefs(get_class($oDbObject));

		// Search for AttributeExternalKey...
		foreach ($aAttDefs as $oAttDef) {

			if ($oAttDef instanceof AttributeExternalKey) {

				// External key target class
				$oLinkedObjectClass = $oAttDef->GetTargetClass();

				// External key target id
				$oLinkedObjectKey = $oDbObject->Get($oAttDef->GetCode());

				if ($oLinkedObjectKey > 0) {

					// Linked object
					$oLinkedObject = MetaModel::GetObject($oLinkedObjectClass, $oLinkedObjectKey);

					// If temporary object descriptors array contains the target object
					$oTemporaryObjectDescriptor = TemporaryObjectHelper::FindTemporaryObjectDescriptors($aTemporaryObjectDescriptors, $oLinkedObjectClass, $oLinkedObjectKey);
					if ($oTemporaryObjectDescriptor !== null) {

						// Add to valid objects
						$aValidTemporaryObjectDescriptors[] = $oTemporaryObjectDescriptor;
					}

					// Deep processing
					$this->ProcessObjectExternalKeys($oLinkedObject, $aTemporaryObjectDescriptors, $aValidTemporaryObjectDescriptors);
				}

			}
		}

	}
}
