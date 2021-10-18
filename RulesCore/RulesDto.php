<?php


namespace Local\Classes\Validator\RulesCore;


use Local\Classes\Validator\Contracts\RulesDtoInterface;
use Local\Classes\Validator\Contracts\ValidationRuleInterface;


/**
 * RulesDto class
 *
 * Класс для {@see \Local\Classes\Validator\RulesCore\RulesManager} чтобы иметь удобный и типизированный доступ к
 * правилам валидации
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
final class RulesDto implements RulesDtoInterface
{
	/**
	 * Rule object instance
	 *
	 * @var ValidationRuleInterface
	 */
	private $instance;
	
	/**
	 * Тип правила валидации
	 *
	 * @var string
	 */
	private $type;
	
	/**
	 * Список функций валидации
	 *
	 * @var array<string, string>
	 */
	private $validationFunctionsWithNames;
	
	/**
	 * @param  ValidationRuleInterface  $instance
	 * @param  string                   $type
	 * @param  string[]                 $validationFunctions
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function __construct(ValidationRuleInterface $instance, string $type, array $validationFunctions)
	{
		$this->instance = $instance;
		$this->type = $type;
		$this->validationFunctionsWithNames = $validationFunctions;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getInstance() : ValidationRuleInterface
	{
		return $this->instance;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getType() : string
	{
		return $this->type;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getValidationFunctionsWithNames() : array
	{
		return $this->validationFunctionsWithNames;
	}
	
	/**
	 * @inheritDoc
	 */
	public static function getNewInstance(ValidationRuleInterface $instance,
										  string                  $type,
										  array                   $validationFunctionsWithNames) : RulesDtoInterface
	{
		return new self($instance, $type, $validationFunctionsWithNames);
	}
	
}