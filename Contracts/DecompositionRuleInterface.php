<?php


namespace Local\Classes\Validator\Contracts;

/**
 * DecompositionRuleInterface
 *
 * Интерфейс DTO объекта для пользовательских правил валидации.
 * Чтобы не парсить по 100500 раз составные правила **rule:argument**, проще 1 раз где-то в
 * {@see \Local\Classes\Validator\Contracts\RulesManagerInterface::getDecompositionRule() Манагере}
 * все распарсить и потом отдавать готовые данные
 *
 * @noinspection SpellCheckingInspection
 *
 * @author       Dmitry Belikov <megaamozzg@gmail.com>
 */
interface DecompositionRuleInterface
{
	/**
	 * Получить правило валидации
	 *
	 * @return string
	 *
	 * @see    \Local\Classes\Validator\Contracts\ValidationRuleInterface::getValidationFunctions()
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getRule() : string;
	
	/**
	 * Получить аргумент правила, если он есть
	 *
	 * @return mixed|null
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function getArgument();
	
	/**
	 * Проверка существования аргумента
	 *
	 * @return bool
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function argumentExist() : bool;
	
	/**
	 * Получить инстанс дто
	 *
	 * @param  string  $rule
	 * @param          $argument
	 *
	 * @return \Local\Classes\Validator\Contracts\DecompositionRuleInterface
	 * @noinspection SpellCheckingInspection
	 *
	 * @author       Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public static function getNewInstance(string $rule, $argument = null) : DecompositionRuleInterface;
	
}