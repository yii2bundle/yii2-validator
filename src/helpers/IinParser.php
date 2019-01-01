<?php

namespace yii2lab\validator\helpers;

use Exception;
use Yii;

class IinParser {
	
	const IIN_LENGTH = 12;
	
	/**
	 * @param $value
	 *
	 * @return mixed
	 * @throws Exception
	 * @deprecated use \yii2woop\operation\domain\v2\helpers\iin\IinParser::parse()
	 */
	public static function parse($value) {
		self::validate($value);
		$part['date'] = IinDateHelper::parseDate($value);
		$part['number'] = substr($value, 7, 4);
		$part['sex'] = self::getSex($value);
		//self::validateSum($value);
		return $part;
	}
	
	private static function validateIsNumeric($value) {
		$value = strval($value);
		if (!is_numeric($value)) {
			throw new Exception();
		}
	}
	
	private static function validateLength($value) {
		$value = strval($value);
		if (strlen($value) != self::IIN_LENGTH) {
			throw new Exception(Yii::t('yii', '{attribute} should contain at most {max, number} {max, plural, one{character} other{characters}}.', [
				'max' => self::IIN_LENGTH,
			]));
		}
	}
	
	private static function validate($value) {
		self::validateIsNumeric($value);
		self::validateLength($value);
	}
	
	private static function validateSum($value) {
		$sum = intval(substr($value, 11, 1));
		$sumCalculated = self::generateSum($value);
		if($sum != $sumCalculated) {
			throw new Exception();
		}
	}
	
	private static function getSex($value) {
		$century = IinDateHelper::parseCentury($value);
		return !empty($century % 2) ? 'male' : 'female';
	}
	
	private static function generateSum($inn) {
		$multiplication = 7 * $inn[0] + 2 * $inn[1] + 4 * $inn[2] + 10 * $inn[3] + 3 * $inn[4] + 5 * $inn[5] + 9 * $inn[6] + 4 * $inn[7] + 6 * $inn[8] + 8 * $inn[9];
		$sum = $multiplication % 11 % 10;
		return $sum;
	}
	
}
