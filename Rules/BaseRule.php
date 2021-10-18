<?php


namespace Local\Classes\Validator\Rules;


use Local\Classes\Validator\Exceptions\ValidationPropertyException;
use Local\Classes\Validator\RulesCore\AbstractRule;


/**
 * BaseRule
 *
 * Реализует базовые функции валидации, по типу:
 *            обязательно должно быть
 *            является строкой или массивам
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
class BaseRule extends AbstractRule
{
	/**
	 * @inheritDoc
	 */
	public function getRuleType() : string
	{
		return '';
	}
	
	/**
	 * Проверка на обязательность заполнения
	 *
	 * @param $validationData
	 *
	 * @throws \Local\Classes\Validator\Exceptions\ValidationPropertyException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function requiredValidate($validationData)
	{
		if (!$validationData)
			throw new ValidationPropertyException('required');
	}
	
}