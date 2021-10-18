<?php


namespace Local\Classes\Validator\Contracts;

/**
 * RulesManagerInterface
 *
 * Интерфейс для менеджера правил. Обязательный компонент в валидаторе
 *
 * @noinspection SpellCheckingInspection
 *
 * @author       Dmitry Belikov <megaamozzg@gmail.com>
 */
interface RulesManagerInterface
{
	/**
	 * Проверить значение по правилу
	 *
	 * @param  mixed   $value  Проверяемое значение
	 * @param  string  $rule   Правило для проверки
	 *
	 * @return void
	 *
	 * @throws \Local\Classes\Validator\Exceptions\RulesConfigNotLoadedException
	 * @throws \Local\Classes\Validator\Exceptions\ValidationPropertyException
	 * @throws \Local\Classes\Validator\Exceptions\RuleNotFoundException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function checkValueByRule($value, string $rule) : void;
	
	/**
	 * Правило является составным?
	 *
	 * "string_length_min:2" - является составным правилом, где "string_length_min" - это само правило, а "2" - это
	 * аргумент (критерий) проверки
	 *
	 * @param  string  $rule
	 *
	 * @return bool
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function isRuleComposite(string $rule) : bool;
	
	/**
	 * Получить DTO объект пользовательского правила
	 *
	 * Разбить правило на правило и аргумент
	 *
	 * @param  string  $rule
	 *
	 * @return DecompositionRuleInterface
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getDecompositionRule(string $rule) : DecompositionRuleInterface;
	
	/**
	 * Получить список всех доступных правил
	 *
	 * @return string[]
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getRulesList() : array;
	
}