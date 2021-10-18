<?php

namespace Local\Classes\Validator;


use Local\Classes\Validator\Contracts\DecompositionRuleInterface;
use Local\Classes\Validator\Contracts\RulesManagerInterface;
use Local\Classes\Validator\Contracts\ValidatorInterface;
use Local\Classes\Validator\Exceptions\RuleNotFoundException;
use Local\Classes\Validator\Exceptions\RulesConfigNotLoadedException;
use Local\Classes\Validator\Exceptions\ValidationPropertyException;
use Local\Classes\Validator\Exceptions\ValidatorException;
use Local\Classes\Validator\RulesCore\RulesManager;


/**
 * Validator class
 *
 * Валидатор данных с возможностью настройки правил, сообщений об ошибках, получение только валидных данных и т.д
 *
 * @internals ЕСЛИ ДОБАВЛЯЕШЬ СЮДА ПУБЛИЧНЫЙ МЕТОД - ОБЯЗАТЕЛЬНО ДОБАВЬ ЕГО ЧЕРЕЗ ИНТЕРФЕЙС.
 *            Также, если требуется, добавь его в фасад
 *
 * @noinspection SpellCheckingInspection
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
class Validator implements ValidatorInterface
{
	/**
	 * Менеджер правил валидации
	 *
	 * @var RulesManagerInterface;
	 */
	private $rulesManager;
	
	/**
	 * Ошибки валидации данных
	 *
	 * @var array<string, string>
	 */
	private $errors = [];
	
	/**
	 * Пользовательские ошибки валидации
	 *
	 * @var array<string, string>
	 */
	private $errorMessages = [];
	
	/**
	 * Пользовательские правила валидации
	 *
	 * @var array<string>
	 */
	private $rules = [];
	
	/**
	 * Пользовательские данные для валидации
	 *
	 * @var array<string, mixed>
	 * @noinspection GrazieInspection
	 */
	private $validateData = [];
	
	/**
	 * Validator constructor
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	public function __construct()
	{
		$this->loadValidationRules();
	}
	
	/**
	 * Добавить сообщение об ошибке
	 *
	 * @param  string       $property  Свойство к которому надо добавить ошибку
	 * @param  string       $message   Сообщение об ошибке
	 * @param  string|null  $rule      Правило по которому не прошли
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function addError(string $property, string $message, ?string $rule = null): void
	{
		if ($rule)
			$this->errors[$property][$rule] = $message;
		else
			$this->errors[$property][] = $message;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getFirstError() : string
	{
		return array_shift(array_shift($this->errors())) ?? '';
	}
	
	/**
	 * Загрузчик классов правил валидации
	 *
	 * @noinspection PhpRedundantCatchClauseInspection
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function loadValidationRules(): void
	{
		try
		{
			$this->rulesManager = new RulesManager();
		}
		catch (ValidatorException $exception)
		{
			$this->addError('RulesManager', $exception->getMessage());
		}
	}
	
	/**
	 * Установить сообщение об ошибке если не были указаны пользовательские правила валидации
	 *
	 * @return void
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function setRulesEmptyErrorMessage(): void
	{
		$this->addError(
			'Validator',
			'Are you seriously? Validation rules are empty! There is nothing to validate!'
		);
	}
	
	/**
	 * Установить данные
	 *
	 * Обёртка интерфейсных методов для реализации массового заполнения
	 *
	 * @param  array<string, string>  $rules           Правила валидации
	 * @param  array<string, mixed>   $validationData  Данные для проверки
	 * @param  array<string, string>  $messages        Сообщения об ошибках валидации
	 *
	 * @noinspection SpellCheckingInspection
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function setProperties(array $rules = [], array $validationData = [], array $messages = []): void
	{
		if ($rules)
			$this->setRules($rules);
		
		if ($validationData)
			$this->setValidationData($validationData);
		
		if ($messages)
			$this->setErrorMessages($messages);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setRules(array $rules) : ValidatorInterface
	{
		$result = [];
		foreach ($rules as $varName => $rule)
		{
			$parsedRule = explode('|', $rule);
			$result[$varName] = $parsedRule;
		}
		
		$this->rules = $result;
		
		return $this;
	}
	
	/**
	 * @inheritDoc
	 */
	public function setErrorMessages(array $messages) : ValidatorInterface
	{
		$this->errorMessages = $messages;
		
		return $this;
	}
	
	/**
	 * @inheritDoc
	 */
	public function setValidationData(array $validationData) : ValidatorInterface
	{
		$this->validateData = $validationData;
		
		return $this;
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate(array $rules = [], array $validationData = [], array $messages = []) : bool
	{
		$this->setProperties($rules, $validationData, $messages);
		
		if (!$this->rules)
			$this->setRulesEmptyErrorMessage();
		
		foreach ($this->rules as $property => $rules)
		{
			foreach ($rules as $rule)
			{
				try
				{
					$this->rulesManager->checkValueByRule($this->validateData[$property], $rule);
				}
				catch (ValidationPropertyException $exception)
				{
					$decompositionRule = $this->rulesManager->getDecompositionRule($rule);
					
					$this->addError(
						$property,
						$this->getPreparedErrorMessage($property, $decompositionRule),
						$decompositionRule->getRule()
					);
				}
				catch (RuleNotFoundException | RulesConfigNotLoadedException $e)
				{
					$this->addError('RulesManager', $e->getMessage());
				}
			}
		}
		
		return $this->isValidated();
	}
	
	/**
	 * Получить сообщение об ошибке валидации параметра
	 *
	 * @param  string|int  					$property           Имя проверяемой переменной
	 * @param  DecompositionRuleInterface 	$decompositionRule	Объекта класса разбитого правила
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function getPreparedErrorMessage($property, DecompositionRuleInterface $decompositionRule): string
	{
		if ($this->errorMessages[$property][$decompositionRule->getRule()])
			$message = $this->errorMessages[$property][$decompositionRule->getRule()];
		else
			$message = "Invalid property - $property. Validation failed in rule - {$decompositionRule->getRule()}";
		
		return $message;
	}
	
	/**
	 * @inheritDoc
	 */
	public function isValidated() : bool
	{
		return !$this->errors;
	}
	
	/**
	 * @inheritDoc
	 */
	public function errors() : array
	{
		return $this->errors;
	}
	
	/**
	 * @inheritDoc
	 */
	public function validated() : array
	{
		$result = [];
		
		if ($this->isValidated())
			foreach (array_keys($this->rules) as $property)
				$result[$property] = $this->validateData[$property];
		
		return $result;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRulesList() : array
	{
		return $this->rulesManager->getRulesList();
	}
	
}
