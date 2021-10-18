# Классы правил и правила валидации

## Общие правила

 - Каждый класс правила **обязан** именоваться как ```TypeRule.php``` 
    > Имя класса обязательно в `StudlyCaps`. Внимание пакет работает по **PSR-0**. Подробнее смотри в
    [PSR-1 p.3 Namespace and Class Names](https://www.php-fig.org/psr/psr-1/)

   > ```type``` - тип (группа) правил. Существительное, обозначающее, что это за правила (например правила для строк будут
   > называться StringRule)
   > 
   > ```Rule``` - обязательный постфикс. **ТОЛЬКО** с заглавной. Обозначает что это класс правил, а так же участвует в
   > формировании типа правил (подробнее смотри в [AbstractRule](../RulesCore/AbstractRule.php) а так же в
   > [RulesManager::getParsedClassName()](../RulesCore/RulesManager.php))

 - Каждый класс правил **обязан** наследоваться от [AbstractRule::class](../RulesCore/AbstractRule.php)
 - Если тип правила (имя класса) имеет сложное название (например ```FooBarStringTypeRule```):
   1. Строит задуматься, а всё ли я правильно сделал? **Может быть есть вариант проще**?
   2. Если предыдущий вариант - не вариант, и таких правил ещё никто не реализовал, то можно переопределить метод
      [getRuleType(): string](../Contracts/ValidationRuleInterface.php) и указать желаемое имя типа
   > ВАЖНО! Имя типа обязательно в виде ```foo_bar_string_type_rule```
 - Методы в классе правила (функции валидации):
    1. Только ```public```
    2. Имеют постфикс ```Validate```
   > По постфиксу ```Validate``` отбираются исполняемые методы.
   >> RulesManager собирает правила валидации опираясь на постфикс ```Validate```
 - Все классы правил которые используются в пакете должны быть подключены в конфигурационном файле [config.php](config.php)
 
## Как это работает

### _Важное замечание про переопределение типа правил_
Метод [getRuleType(): string](../Contracts/ValidationRuleInterface.php) это как кнопка извещения о пожаре. 
Чем реже используешь тем лучше. А уж если использовал, то это должно быть **максимально оправдано**
> Например, как с базовыми проверками. Есть класс `BaseRule` в нём живёт правило `required`.
>И чтобы это правило `required` было `required`, а не `base_required` пришлось использовать переопределение типа
> и ставить `return '';`

> Если класс правила называется `ArrayStdObjectRule`, то без переопределения его тип будет `array_std_object`

### Как создать правило

> P.S. Перед тем как создавать своё правило убедись что до тебя этого ещё никто не сделал.
>> Полный список правил можно увидеть вызвав метод ```(new \Local\Classes\Validator\Validator)->getRulesList();```

Для примера возьмём правило проверки минимальной длины строки.

Из названия `минимальной длины строки` понимаем что имеем дело с типом(`type`) правил для `string` (смотри **Общие правила**).
Ищем класс для данного типа правил. По логике он должен называться `StringRule`.

Открываем данный класс и пишем функцию
```php
/**
 * Правило проверки минимальной длины строки
 *
 * @param  mixed     $validationData
 * @param  int|null  $min
 *
 * @returm void
 * @throws ValidationPropertyException
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
public function lengthMinValidate($validationData, ?int $min = null): void
{
    $length = iconv_strlen($validationData, mb_detect_encoding($validationData));
    
    if ($min && $min !== 0 && $length < $min)
        throw new ValidationPropertyException('string_length_min');
}
```

##### Ключевые моменты
 - функция имеет постфикс `Validate`
 - название функции `lengthMin` будет преобразовано в правило `length_min`, а полное название правила, включая тип, 
будет `string_length_min`
 - Так как функция `void` логика пишется через **отрицание**, то есть если данные **проходят** проверку - ничего
не возвращается, а если **не проходят**, то обязана вернуть 
[ValidationPropertyException](../Exceptions/ValidationPropertyException.php)

### Как создать класс правил
1. Определяемся с типом. Пусть это будет **Array**
2. В папке **Rules** Создаём класс **ArrayRule.php**
```php
<?php


namespace Local\Classes\Validator\Rules;


use Local\Classes\Validator\Exceptions\ValidationPropertyException;
use Local\Classes\Validator\RulesCore\AbstractRule;


/**
 * ArrayRule
 *
 * Реализует функции проверки массивов
 *
 * @author Dmitry Belikov <megaamozzg@gmail.com>
 */
class ArrayRule extends AbstractRule
{
    #code
}
```

3. Если требуется переопределить тип, переопределяем метод `getRuleType()`

```php
/**
 * @inheritDoc
 */
public function getRuleType() : string
{
    return 'array'; // Если название сложное, то пишем 'foo_bar'
}
```

4. Подключаем свой класс в конфигурационном файле [config.php](config.php)
5. Пишем свои функции (как это делать описано разделом выше)

