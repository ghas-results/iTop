<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * TemporaryObjectHelper.
 *
 * Helper with useful functions.
 *
 * @since 3.1
 */
class TemporaryObjectHelper
{
	const CONFIG_FORCE             = 'external_keys.force_temporary_object_creation';
	const CONFIG_TEMP_LIFETIME     = 'external_keys.temporary_object_lifetime';
	const CONFIG_WATCHDOG_INTERVAL = 'external_keys.watchdog_interval';

	/**
	 * GetTemporaryObjectDescriptionByMeta.
	 *
	 * @param string $sTransactionId
	 * @param array $aMetaValues
	 * @param bool $bReverseOrder
	 *
	 * @return array
	 */
	static public function GetTemporaryObjectDescriptionByMeta(string $sTransactionId, array $aMetaValues, bool $bReverseOrder = false): array
	{
		// Result
		$aCorrespondingTemporaryObjectsDescriptors = [];

		// Retrieve temporary objects of transaction id
		$aTemporaryObjectsDescriptors = TemporaryObjectManager::GetTemporaryObjectsDescriptors($sTransactionId, $bReverseOrder);

		// Compare metadata for each descriptor...
		foreach ($aTemporaryObjectsDescriptors as $oTemporaryObjectDescriptor) {

			// diff flag
			$bDiff = false;

			// Extract meta data
			$sMetadata = $oTemporaryObjectDescriptor->Get('metadata');
			$aMetadata = json_decode($sMetadata, true);

			// Meta entries to compare...
			foreach ($aMetaValues as $sKey => $sValue) {
				if (!key_exists($sKey, $aMetadata) || $aMetadata[$sKey] !== $sValue) {
					$bDiff = true;
					break;
				}
			}

			// No difference
			if (!$bDiff) {
				$aCorrespondingTemporaryObjectsDescriptors[] = $oTemporaryObjectDescriptor;
			}
		}

		return $aCorrespondingTemporaryObjectsDescriptors;
	}

	/**
	 * GetTemporaryObjectDescriptionMeta.
	 *
	 * @param TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	static public function GetTemporaryObjectDescriptionMeta(TemporaryObjectDescriptor $oTemporaryObjectDescriptor): array
	{
		$sMetadata = $oTemporaryObjectDescriptor->Get('metadata');

		return json_decode($sMetadata, true);
	}

	/**
	 * SetTemporaryObjectDescriptionMeta.
	 *
	 * @param \TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 * @param array $aMetaData
	 *
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	static public function SetTemporaryObjectDescriptionMeta(TemporaryObjectDescriptor $oTemporaryObjectDescriptor, array $aMetaData)
	{
		$oTemporaryObjectDescriptor->Set('metadata', $aMetaData);
		$oTemporaryObjectDescriptor->DBUpdate();
	}

	/**
	 * AddTemporaryObjectDescriptionMeta.
	 *
	 * @param \TemporaryObjectDescriptor $oTemporaryObjectDescriptor
	 * @param string $sMetaKey
	 * @param $oMetaValue
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	static public function AddTemporaryObjectDescriptionMeta(TemporaryObjectDescriptor $oTemporaryObjectDescriptor, string $sMetaKey, $oMetaValue)
	{
		$sMetadata = $oTemporaryObjectDescriptor->Get('metadata');
		$aMetadata = json_decode($sMetadata, true);
		$aMetadata[$sMetaKey] = $oMetaValue;
		$oTemporaryObjectDescriptor->Set('metadata', json_encode($aMetadata));
		$oTemporaryObjectDescriptor->DBUpdate();
	}

	static public function FindTemporaryObjectDescriptors(array $aTemporaryObjectDescriptors, string $sClass, string $sKey)
	{
		foreach ($aTemporaryObjectDescriptors as $oTemporaryObjectDescriptor) {
			if ($oTemporaryObjectDescriptor->Get('item_class') === $sClass
				&& $oTemporaryObjectDescriptor->Get('item_id') == $sKey) {
				return $oTemporaryObjectDescriptor;
			}
		}

		return null;
	}

	/**
	 * @param string $sTempId
	 *
	 * @return string
	 */
	static public function GetWatchDogJS(string $sTempId): string
	{
		$iWatchdogInterval = MetaModel::GetConfig()->Get(self::CONFIG_WATCHDOG_INTERVAL);

		return <<<JS
			window.setInterval(function() {
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?route=temporary_object.watch_dog', {temp_id: '$sTempId'});
			}, $iWatchdogInterval * 1000);
JS;
	}


}
