<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class TemporaryObjectDescriptor extends DBObject
{
	public static function Init()
	{
		$aParams = array(
			'category'            => 'structure',
			'key_type'            => 'autoincrement',
			'name_attcode'        => array('item_class', 'temp_id'),
			'image_attcode'       => '',
			'state_attcode'       => '',
			'reconc_keys'         => array(''),
			'db_table'            => 'priv_temporary_object_descriptor',
			'db_key_field'        => 'id',
			'db_finalclass_field' => '',
			'style'               => new ormStyle(null, null, null, null, null, null),
			'indexes'             => array(
				1 =>
					array(
						0 => 'temp_id',
					),
				2 =>
					array(
						0 => 'item_class',
						1 => 'item_id',
					),
			),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime('expire', array('sql' => 'expire', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('temp_id', array('sql' => 'temp_id', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeString('item_class', array('sql' => 'item_class', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeObjectKey('item_id', array('class_attcode' => 'item_class', 'sql' => 'item_id', 'is_null_allowed' => true, 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));
		MetaModel::Init_AddAttribute(new AttributeDateTime('creation_date', array('sql' => 'creation_date', 'is_null_allowed' => true, 'default_value' => '', 'allowed_values' => null, 'depends_on' => array(), 'always_load_in_tables' => false)));


		MetaModel::Init_SetZListItems('details', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
		));
		MetaModel::Init_SetZListItems('standard_search', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
		));
		MetaModel::Init_SetZListItems('list', array(
			0 => 'temp_id',
			1 => 'item_class',
			2 => 'item_id',
			3 => 'creation_date',
		));;
	}


	public function DBInsertNoReload()
	{
		$this->SetCurrentDateIfNull('creation_date');

		return parent::DBInsertNoReload();
	}


	/**
	 * Set/Update all of the '_item' fields
	 *
	 * @param object $oItem Container item
	 *
	 * @return void
	 */
	public function SetItem($oItem, $bUpdateOnChange = false)
	{
		$sClass = get_class($oItem);
		$iItemId = $oItem->GetKey();

		$this->Set('item_class', $sClass);
		$this->Set('item_id', $iItemId);
	}
}
