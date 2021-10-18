<?php


namespace Local\Classes\Validator\RulesCore;


use Local\Classes\Validator\Contracts\ValidationRuleInterface;
use ReflectionClass;
use ReflectionMethod;


/**
 * AbstractRule
 *
 * Главный класс для всех реализаторов функций валидации
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
abstract class AbstractRule implements ValidationRuleInterface
{
	/**
	 * @inheritDoc
	 */
	public function getValidationFunctions() : array
	{
		$result = [];
		
		$class = new ReflectionClass(static::class);
		$reflectionMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
		foreach ($reflectionMethods as $method)
			if (stripos($method->name, 'Validate'))
				$result[$method->name] = $this->formatValidationFunctionName($method->name);
		
		return $result;
	}
	
	/**
	 * Форматировать имя функции
	 *
	 * @param  string  $functionName
	 *
	 * @return string
	 *
	 * @author Dmitry Belikov <megaamozzg@gmail.com>
	 */
	private function formatValidationFunctionName(string $functionName) : string
	{
		$parts = preg_split('/(?=[A-Z])/', $functionName, -1, PREG_SPLIT_NO_EMPTY);
		array_pop($parts);
		
		$resultName = implode('_', $parts);
		
		return strtolower($resultName);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRuleType() : string
	{
		return static::class;
	}
	
}