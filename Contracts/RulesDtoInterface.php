<?php


namespace Local\Classes\Validator\Contracts;

/**
 * RulesDtoInterface class
 *
 * Интерфейс DTO объекта для типизации работы с
 * {@see \Local\Classes\Validator\Contracts\ValidationRuleInterface Классами правил}
 * в {@see \Local\Classes\Validator\Contracts\RulesManagerInterface RulesManager`e}
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
interface RulesDtoInterface
{
	/**
	 * Получить инстанс класса правил
	 *
	 * @return ValidationRuleInterface
	 * @noinspection SpellCheckingInspection
	 *
	 * @author       Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getInstance() : ValidationRuleInterface;
	
	/**
	 * Получить тип правила
	 *
	 * Имеется ввиду базовый тип, правила для: String, Array, и т.д.
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getType() : string;
	
	/**
	 * Получить ассоциативный массив функций валидации к их строковым названиям (правилам)
	 *
	 * @return array<string, string>
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getValidationFunctionsWithNames() : array;
	
	/**
	 * Получить инстанс дто
	 *
	 * @param  ValidationRuleInterface  $instance                      Instance класс правил
	 * @param  string                   $type                          Тип правил
	 * @param  array<string, string>    $validationFunctionsWithNames  Массив [Название функции => название_правила]
	 *
	 * @return RulesDtoInterface
	 * @noinspection SpellCheckingInspection
	 *
	 * @author       Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public static function getNewInstance(ValidationRuleInterface $instance,
										  string                  $type,
										  array                   $validationFunctionsWithNames) : RulesDtoInterface;
	
}