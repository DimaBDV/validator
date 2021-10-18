<?php


namespace Local\Classes\Validator;


use Local\Classes\Validator\Contracts\ValidatorInterface;


/**
 * ValidatorFacade
 *
 * Фасад для доступа к состояние-независимым методам класса {@see ValidatorInterface Validator}
 *
 * @noinspection PhpUnused
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
final class ValidatorFacade
{
	/**
	 * Получить инстанс объета Validator
	 *
	 * @return \Local\Classes\Validator\Contracts\ValidatorInterface
	 *
	 * @noinspection SpellCheckingInspection
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private static function getInstance() : ValidatorInterface
	{
		return new Validator();
	}
	
	/**
	 * Создать объект Validator и наполнить его данными
	 *
	 * @param  array<string, string>                      $rules           Правила валидации
	 * @param  array<string, mixed>                       $validationData  Данные для проверки
	 * @param  array<string, array<string|null, string>>  $messages        Сообщения об ошибках валидации
	 *
	 * @return \Local\Classes\Validator\Contracts\ValidatorInterface
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public static function make(array $rules, array $validationData, array $messages = []) : ValidatorInterface
	{
		return self::getInstance()->setRules($rules)->setValidationData($validationData)->setErrorMessages($messages);
	}
	
	/**
	 * Валидировать данные
	 *
	 * Просто проверить, **без получения ошибок**, только **true** || **false**
	 *
	 * @param  array<string, string>  $rules           Правила валидации
	 * @param  array<string, mixed>   $validationData  Данные для проверки
	 *
	 * @return bool
	 *
	 * @noinspection SpellCheckingInspection
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public static function validate(array $rules, array $validationData) : bool
	{
		return self::getInstance()->validate($rules, $validationData);
	}
	
}