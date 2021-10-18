<?php

namespace Local\Classes\Validator\RulesCore;

use Local\Classes\Validator\Contracts\DecompositionRuleInterface;
use Local\Classes\Validator\Contracts\RulesDtoInterface;
use Local\Classes\Validator\Contracts\RulesManagerInterface;
use Local\Classes\Validator\Contracts\ValidationRuleInterface;
use Local\Classes\Validator\Exceptions\DuplicateRuleInstanceException;
use Local\Classes\Validator\Exceptions\RuleNotFoundException;
use Local\Classes\Validator\Exceptions\RulesConfigNotLoadedException;


/**
 * RulesManager
 *
 * Менеджер правил валидации. Отвечает за загрузку функций, распределение какие данные в какое правило отдать и т.д
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
class RulesManager implements RulesManagerInterface
{
	/**
	 * Классы отвечающие за поддерживаемые правила валидации
	 *
	 * @var array
	 */
	private $rulesClasses;
	
	/**
	 * Контейнер для DTO классов правил валидации
	 *
	 * @var RulesDtoInterface[]
	 */
	private $container = [];
	
	/**
	 * @throws DuplicateRuleInstanceException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function __construct()
	{
		$this->setRulesClasses();
		$this->loadAllRules();
	}
	
	/**
	 * @inheritDoc
	 */
	public function checkValueByRule($value, string $rule): void
	{
		$decompositionRule = $this->getDecompositionRule($rule);
		
		$this->exceptionIfRulesConfigNotLoaded();
		$this->exceptionIfValidationRuleNotExist($decompositionRule);
		
		foreach ($this->container as $rulesDto)
		{
			if (in_array($decompositionRule->getRule(), $rulesDto->getValidationFunctionsWithNames()))
			{
				$method = array_search($decompositionRule->getRule(), $rulesDto->getValidationFunctionsWithNames());
				$instance = $rulesDto->getInstance();
				
				if ($decompositionRule->argumentExist())
					$instance->{$method}($value, $decompositionRule->getArgument());
				else
					$instance->{$method}($value);
			}
		}
	}
	
	/**
	 * Ошибка если классы правил не загружены
	 *
	 * @throws RulesConfigNotLoadedException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function exceptionIfRulesConfigNotLoaded()
	{
		if (!$this->rulesClasses)
			throw new RulesConfigNotLoadedException('Rule configuration file not loaded or missing');
	}
	
	/**
	 * Ошибка если правила валидации не существует
	 *
	 * @param  DecompositionRuleInterface  $decompositionRule
	 *
	 * @return void
	 *
	 * @throws RuleNotFoundException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function exceptionIfValidationRuleNotExist(DecompositionRuleInterface $decompositionRule): void
	{
		$allRules = $this->getRulesList();
		$exist = false;
		
		foreach ($allRules as $rules)
			if (in_array($decompositionRule->getRule(), $rules))
				$exist = true;
			
		if (!$exist)
			throw new RuleNotFoundException(
				"Rule {$decompositionRule->getRule()} is missing or not loaded or has a different name"
			);
	}
	
	/**
	 * @inheritDoc
	 */
	public function isRuleComposite(string $rule) : bool
	{
		return stripos($rule, ':') !== false;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getDecompositionRule(string $rule) : DecompositionRuleInterface
	{
		if ($this->isRuleComposite($rule))
		{
			$exploded = explode(':', $rule);
			
			return DecompositionRule::getNewInstance($exploded[0], $exploded[1]);
		}
		
		return DecompositionRule::getNewInstance($rule);
	}
	
	/**
	 * Добавить DTO правила в контейнер
	 *
	 * @param  RulesDtoInterface  $dto
	 *
	 * @throws DuplicateRuleInstanceException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function addContainer(RulesDtoInterface $dto): void
	{
		$this->checkDuplicateTypeRule($dto);
		$this->container[] = $dto;
	}
	
	/**
	 * Получить все поддерживаемые правила валидации
	 *
	 * @return void
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function setRulesClasses(): void
	{
		$file = __DIR__ . '/../Rules/config.php';
		if (file_exists($file))
			$config = include ($file);
		
		$this->rulesClasses = $config ?? [];
	}
	
	/**
	 * Загрузка всех доступных правил валидации
	 *
	 * @throws DuplicateRuleInstanceException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function loadAllRules(): void
	{
		foreach ($this->rulesClasses as $rulesClass)
		{
			$ruleClassInstance = $this->getInstanceRuleClass($rulesClass);
			$ruleType = $this->getRuleType($ruleClassInstance);
			$ruleValidationFunctionsWithNames = $this->getRuleValidationFunctions($ruleType, $ruleClassInstance);
			
			$this->addContainer(
				RulesDto::getNewInstance($ruleClassInstance, $ruleType, $ruleValidationFunctionsWithNames)
			);
		}
	}
	
	/**
	 * Получить instance класса правил валидации
	 *
	 * @param  string  $class
	 *
	 * @return ValidationRuleInterface
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function getInstanceRuleClass(string $class): ValidationRuleInterface
	{
		return new $class;
	}
	
	/**
	 * Получить тип правила валидации
	 *
	 * @param  ValidationRuleInterface  $ruleClass
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function getRuleType(ValidationRuleInterface $ruleClass): string
	{
		$className = $ruleClass->getRuleType();
		if (stripos($className, '\\') !== false)
			$className = $this->getParsedClassName($ruleClass);
		
		return $className;
	}
	
	/**
	 * Получить имя класса без namespace и Rule в названии
	 *
	 * @param  ValidationRuleInterface  $ruleClass
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function getParsedClassName(ValidationRuleInterface $ruleClass): string
	{
		$shortClassName = array_pop(explode('\\', $ruleClass->getRuleType()));
		$className = array_shift(explode('Rule', $shortClassName));
		
		$parts = preg_split('/(?=[A-Z])/', $className, -1, PREG_SPLIT_NO_EMPTY);
		if (count($parts) > 1)
			$className = implode('_', $parts);
		
		return strtolower($className);
	}
	
	/**
	 * Получить список функций валидации
	 *
	 * @param  string                   $ruleType
	 * @param  ValidationRuleInterface  $ruleClass
	 *
	 * @return array
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function getRuleValidationFunctions(string $ruleType, ValidationRuleInterface $ruleClass): array
	{
		$rawValidationFunctions = $ruleClass->getValidationFunctions();
		
		$result = [];
		foreach ($rawValidationFunctions as $validationFunction => $ruleAlias)
			if (!$ruleType)
				$result[$validationFunction] = "$ruleAlias";
			else
				$result[$validationFunction] = "{$ruleType}_$ruleAlias";
		
		return $result;
	}
	
	/**
	 * Проверка типов правил на дубликат
	 *
	 * @param  RulesDtoInterface  $newRulesDto
	 *
	 * @throws DuplicateRuleInstanceException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function checkDuplicateTypeRule(RulesDtoInterface $newRulesDto): void
	{
		foreach ($this->container as $rulesDto)
			if ($rulesDto->getType() === $newRulesDto->getType())
				$this->duplicateRulInstanceException($newRulesDto, $rulesDto);
	}
	
	/**
	 * Ошибка об дублировании правила
	 *
	 * @throws DuplicateRuleInstanceException
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function duplicateRulInstanceException(RulesDtoInterface $duplicate, RulesDtoInterface $compared): void
	{
		$class = get_class($duplicate->getInstance());
		$comparedToClass = get_class($compared->getInstance());
		throw new DuplicateRuleInstanceException("Class $class is of the same type as $comparedToClass");
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRulesList() : array
	{
		$result = [];
		
		foreach ($this->container as $rulesDto)
			$result[$rulesDto->getType()] = array_values($rulesDto->getValidationFunctionsWithNames());
		
		return $result;
	}
}