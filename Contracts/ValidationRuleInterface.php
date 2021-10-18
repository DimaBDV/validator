<?php


namespace Local\Classes\Validator\Contracts;

/**
 * ValidationRuleInterface
 *
 * Интерфейс реализаторов правил валидации.
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
interface ValidationRuleInterface
{
	/**
	 * Получить функции проверки
	 *
	 * @return array
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getValidationFunctions() : array;
	
	/**
	 * Тип правила
	 *
	 * Можно вернуть пустую строку, тогда {@see \Local\Classes\Validator\RulesManager::getRuleType()} сам определит
	 * тип правила. Но если у вас сложное название класса правил, то указание типа обязательно!
	 *
	 * @return string    Имя класса
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getRuleType() : string;
	
}