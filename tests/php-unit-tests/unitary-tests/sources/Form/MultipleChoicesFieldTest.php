<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Form;

use Combodo\iTop\Form\Field\MultipleChoicesField;
use Combodo\iTop\Form\Field\MultipleSelectField;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ContextTag;

class MultipleChoicesFieldTest extends ItopTestCase {
	public function testValidate() : void {
		$oMultipleChoicesField = new MultipleSelectField('test');
		$oMultipleChoicesField->AddChoice('A');
		$oMultipleChoicesField->AddChoice('B');
		$oMultipleChoicesField->AddChoice('C');
		$oMultipleChoicesField->AddChoice('D');

		// N°1150 the control was added for the REST API initially and was only triggered with the corresponding ContextTag
		$oRestContext = new ContextTag(ContextTag::TAG_REST);
		$this->ValidateMultipleChoicesField($oMultipleChoicesField);

		// retrying without REST context
		unset($oRestContext);
		$this->ValidateMultipleChoicesField($oMultipleChoicesField);
	}

	private function ValidateMultipleChoicesField(MultipleChoicesField $oMultipleChoicesField): void {
		$oMultipleChoicesField->SetCurrentValue(null);
		$this->assertTrue($oMultipleChoicesField->Validate(), 'No value must be valid');

		$sExistingValue = 'A';
		$sNonExistingValue = 'Non existing choice';
		$sNonExistingValue1 = 'Non existing choice 1';
		$sNonExistingValue2 = 'Non existing choice 2';

		$oMultipleChoicesField->SetCurrentValue($sExistingValue);
		$this->assertTrue($oMultipleChoicesField->Validate(), 'Value among possible ones is valid');

		$oMultipleChoicesField->SetCurrentValue($sNonExistingValue);
		$this->assertFalse($oMultipleChoicesField->Validate(), 'Value not among possible ones is invalid');
		$this->assertCount(1, $oMultipleChoicesField->GetErrorMessages());
		$this->assertStringContainsString($sNonExistingValue, $oMultipleChoicesField->GetErrorMessages()[0]);

		$oMultipleChoicesField->SetCurrentValue([$sNonExistingValue1, $sNonExistingValue2]);
		$this->assertFalse($oMultipleChoicesField->Validate(), 'Multiple values not among possible ones is invalid');
		$this->assertCount(2, $oMultipleChoicesField->GetErrorMessages());
		$this->assertStringContainsString($sNonExistingValue1, $oMultipleChoicesField->GetErrorMessages()[0]);
		$this->assertStringContainsString($sNonExistingValue2, $oMultipleChoicesField->GetErrorMessages()[1]);

		$oMultipleChoicesField->SetCurrentValue([$sExistingValue, $sNonExistingValue]);
		$this->assertFalse($oMultipleChoicesField->Validate(), 'Valid value + Value not among possible ones is invalid');
		$this->assertCount(1, $oMultipleChoicesField->GetErrorMessages());
		$this->assertStringContainsString($sNonExistingValue, $oMultipleChoicesField->GetErrorMessages()[0]);

		$oMultipleChoicesField->SetCurrentValue([$sExistingValue, $sNonExistingValue1, $sNonExistingValue2]);
		$this->assertFalse($oMultipleChoicesField->Validate(), 'Valid value + Multiple values not among possible ones is invalid');
		$this->assertCount(2, $oMultipleChoicesField->GetErrorMessages());
		$this->assertStringContainsString($sNonExistingValue1, $oMultipleChoicesField->GetErrorMessages()[0]);
		$this->assertStringContainsString($sNonExistingValue2, $oMultipleChoicesField->GetErrorMessages()[1]);
	}
}