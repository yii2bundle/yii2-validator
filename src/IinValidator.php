<?php

namespace yii2lab\validator;

use yii2lab\validator\helpers\IinParser;
use Exception;
use Yii;
use yii\validators\Validator;

class IinValidator extends Validator {
	
	private $length = 12;
	
	public function validateAttribute($model, $attribute) {
		$value = strval($model->$attribute);
		$this->validateIsNumeric($model, $attribute);
		$this->validateLength($model, $attribute);
		try {
			$info = IinParser::parse($value);
		} catch (Exception $e) {
			$this->addError($model, $attribute, 'not_valid_inn');
		}
	}
	
	private function validateIsNumeric($model, $attribute) {
		$value = strval($model->$attribute);
		if (!is_numeric($value)) {
			$notEqual = Yii::t('yii', '{attribute} must be a number.');
			$this->addError($model, $attribute, $notEqual, ['attribute' => $attribute]);
			return;
		}
	}
	
	private function validateLength($model, $attribute) {
		$value = strval($model->$attribute);
		if (strlen($value) != 12) {
			$notEqual = Yii::t('yii', '{attribute} should contain {length, number} {length, plural, one{character} other{characters}}.');
			$this->addError($model, $attribute, $notEqual, ['length' => $this->length]);
			return;
		}
	}
	
}
