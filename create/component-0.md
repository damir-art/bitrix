# Создание пользовательского компонента 0
Пользовательские компоненты можно хранить в:
- /bitrix/components/
- /local/components/

В папке `/local/components/` обычно размещаются компоненты которые пишутся под конкретный проект и стандартные кастомизированные компоненты битрикса.

Разместим наш компонент в папке `/local/components/userspace/componentname/`, где `userspace` это наше пространство имён, у CMS Битрикс пространство имен `bitrix`.

Рассмотрим минимальную структуру папок и файлов компонента Битрикс:
- component.php - логика компонента
- templates/.default/template.php - шаблон компонента по умолчанию

В начале каждого PHP-файла нужно разместить:

    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

## Подключение шаблона
`component.php`:

    <? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

    echo "<pre>";
    print_r($arParams);
    echo "</pre>";

    $arResult["P1"] = $arParams["PARAM1"]; // Передаем параметры в $arResult

    $this->includeComponentTemplate(); // подключение шаблона

## Шаблон компонента

    <? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

    echo "<pre>";
    print_r($arResult);
    echo "</pre>";

    echo "Привет, это шаблон компонента";
    echo "В шаблоне компонента можно выводить параметры комопнента " . $arParams["PARAM2"];

## Вызов компонента
Переходим на главную страницу, создаём файл `test.php`, в нём вызываем компонент:

    $APPLICATION->IncludeComponent(
        "userspace:componentname", // расположение компонента
        "", // шаблон компонента, по умолчанию .default
        Array(
            // входные параметры
        )
    );

## Входные параметры
Входные параметры вызова компонента:

    Array(
        "PARAM1" => "Первый параметр",
        "PARAM2" => 100,
    )

- Вывод параметров `print_r($arParams)` можно размещать в файле `component.php`.
- Вывод результата `print_r($arResult)` можно размещать в файле `template.php`.
- `$arResult()` формируют в `component.php` до `$this->includeComponentTemplate()`.
- `$arParams()` приходит в компонент.
- `$arResult()` приходит в шаблон.
---
- class.php - точка входа
- .description.php - описание компонента
- lang/ru/.description.php - языковой файл
- templates/.default/template.php - шаблон компонента
- .parametrs.php - передать начальные значения, отображение полей для ввода параметров
