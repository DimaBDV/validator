<?php


namespace Local\Classes\Validator\Contracts;

/**
 * ValidatorInterface
 *
 * Интерфейс базового класса валидатора
 *
 * @author       Dmitry Belikov <megaamozzg@gmail.com>
 * @noinspection SpellCheckingInspection
 */
interface ValidatorInterface
{
	/**
	 * Валидировать данные
	 *
	 * @noinspection SpellCheckingInspection
	 *
	 * @param  array<string, string>                      $rules           Правила валидации
	 * @param  array<string, mixed>                       $validationData  Данные для проверки
	 * @param  array<string, array<string|null, string>>  $messages        Сообщения об ошибках валидации
	 *
	 * @return bool
	 * @author       Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function validate(array $rules = [], array $validationData = [], array $messages = []) : bool;
	
	/**
	 * Установить данные для проверки
	 *
	 * @param  array<string, mixed>  $validationData  Данные для проверки
	 *
	 * @return \Local\Classes\Validator\Contracts\ValidatorInterface
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function setValidationData(array $validationData) : ValidatorInterface;
	
	/**
	 * Установить правила валидации
	 *
	 * @param  array<string, string>  $rules  Правила валидации
	 *
	 * @return \Local\Classes\Validator\Contracts\ValidatorInterface
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function setRules(array $rules) : ValidatorInterface;
	
	/**
	 * Установить сообщения об ошибках для валидируемых полей
	 *
	 * @param  array<string, array<string|null, string>>  $messages  Сообщения об ошибках валидации
	 *
	 * @return \Local\Classes\Validator\Contracts\ValidatorInterface
	 * @noinspection SpellCheckingInspection
	 *
	 * @author       Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function setErrorMessages(array $messages) : ValidatorInterface;
	
	/**
	 * Получение результата валидации
	 *
	 * @return bool
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function isValidated() : bool;
	
	/**
	 * Получить все ошибки
	 *
	 * @return array
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function errors() : array;
	
	/**
	 * Получить первую ошибку из списка
	 *
	 * Достаёт самую первую ошибку, получим только текст, а переменную к которой эта ошибка относится уже не получим
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getFirstError() : string;
	
	/**
	 * Вернёт только валидные входные данные
	 *
	 * При условии, что нет ошибок валидации
	 *
	 * @return array
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function validated() : array;
	
	/**
	 * Получить список всех доступных правил
	 *
	 * @return array<string, string[]>
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getRulesList() : array;
	
}