<?php


/**
 * Классы отвечающие за поддерживаемые правила валидации
 *
 * @internal     Фактически это - Plug&Play. Если после добавления своего класса у тебя что-то отвалилось или не
 *               работает, то ищи косяк у себя, ядро работает исправно
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
return [
	\Local\Classes\Validator\Rules\BaseRule::class,
	\Local\Classes\Validator\Rules\StringRule::class,
];