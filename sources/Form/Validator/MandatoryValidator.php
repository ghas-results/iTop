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

namespace Combodo\iTop\Form\Validator;

/**
 * Description of MandatoryValidator
 *
 * MandatoryValidator is different than NotEmptyValidator as it doesn't apply to text input only
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class MandatoryValidator extends Validator
{
	const VALIDATOR_NAME = 'mandatory';
	const DEFAULT_REGEXP = '.*\S.*';
	const DEFAULT_ERROR_MESSAGE = 'Core:Validator:Mandatory';

}
