<?php


namespace Local\Classes\Validator\Rules;


use Local\Classes\Validator\Exceptions\ValidationPropertyException;
use Local\Classes\Validator\RulesCore\AbstractRule;


/**
 * StringRule
 *
 * Реализует функции проверки строк
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
class StringRule extends AbstractRule
{
	/**
	 * Правило проверки минимальной длины строки
	 *
	 * @param  mixed     $validationData
	 * @param  int|null  $min
	 *
	 * @return void
	 * @throws ValidationPropertyException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function lengthMinValidate($validationData, ?int $min = null) : void
	{
		$length = iconv_strlen($validationData, mb_detect_encoding($validationData));
		
		if ($min && $min !== 0 && $length < $min)
			throw new ValidationPropertyException('string_length_min');
	}
	
}