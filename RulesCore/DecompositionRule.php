<?php


namespace Local\Classes\Validator\RulesCore;


use Local\Classes\Validator\Contracts\DecompositionRuleInterface;


/**
 * DecompositionRule
 *
 * DTO объект для пользовательских правил валидации.
 * Чтобы не парсить по 100500 раз составные правила **rule:argument**, проще 1 раз где-то в
 * {@see \Local\Classes\Validator\Contracts\RulesManagerInterface::getDecompositionRule() Манагере}
 * все распарсить и потом отдавать готовые данные
 *
 * @noinspection SpellCheckingInspection
 * @author       Dmitry Belikov <megaamozzg@gmail.com>
 */
final class DecompositionRule implements DecompositionRuleInterface
{
	/**
	 * Правило
	 *
	 * @var string
	 */
	private $rule;
	
	/**
	 * Аргумент правила
	 *
	 * @var mixed
	 */
	private $argument;
	
	/**
	 * DecompositionRule constructor
	 *
	 * @param  string  $rule      Правило
	 * @param  mixed   $argument  Аргумент правила
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function __construct(string $rule, $argument)
	{
		$this->rule = $rule;
		$this->argument = $argument;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRule() : string
	{
		return $this->rule;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getArgument()
	{
		return $this->argument;
	}
	
	/**
	 * @inheritDoc
	 */
	public function argumentExist() : bool
	{
		return $this->getArgument() !== null;
	}
	
	/**
	 * @inheritDoc
	 */
	public static function getNewInstance(string $rule, $argument = null) : DecompositionRuleInterface
	{
		return new self($rule, $argument);
	}
	
}