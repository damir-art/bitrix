# Страницы

## Страницы и разделы
В публичной части можно создавать страницы и разделы.

Разделы влияют на страницы находящиеся в нем, например если у раздела задан title а у страницы нет, то у страницы будет title как у раздела, тоже работает и для подразделов (свойства раздела наследуются подразделами и страницами если они у них не установлены). Еще у раздела есть файл `.section.php` который хранит свойства раздела. Если `.section.php` есть в корне сайта, то его свойства будут влиять на все разделы и страницы сайта, где эти свойства не проставлены.

Страницы можно создавать не только через публичную часть админки но и копируя файлы.

## Страницы шаблона
Страница это PHP-файл, состоящий из:

    header (пролог)
    workarea (тело, рабочая область)
    footer (эпилог)

## Свойства (раздела/страницы)
    
    `.section.php` - свойства разделов

Свойства страницы задаются в теле страницы, между служебной частью и прологом, его визуальной частью. Свойства раздела автоматический наследуются его подразделами и страницами. Свойства можно изменять в каждом резделе/страницы.

## Языковой файл (lang)

Языковой файл - это PHP код, хранящий переводы языковых фраз на тот или иной язык.

Языковые файлы хранятся в папках `/lang/` находящихся в шаблонах сайтов или компонентах, для перевода административной или публичной части сайта/модуля/компонента.

Код состоит из элементов массива `$MESS`, где ключ это идентификатор языковой фразы, а значение - переввод.

Пример кода для русского сайта:

    <?
      $MESS ['SUP_SAVE'] = "Сохранить";
      $MESS ['SUP_APPLY'] = "Применить";
      $MESS ['SUP_RESET'] = "Сбросить";
      $MESS ['SUP_EDIT'] = "Изменить";
      $MESS ['SUP_DELETE'] = "Удалить";
    ?>

Пример кода для английского сайта:

    <?
      $MESS ['SUP_SAVE'] = "Save";
      $MESS ['SUP_APPLY'] = "Apply";
      $MESS ['SUP_RESET'] = "Reset";
      $MESS ['SUP_EDIT'] = "Change";
      $MESS ['SUP_DELETE'] = "Delete";
    ?>

# Свойства страницы/раздела
Свойства страниц устанавливаются в административной панели:

    Админ > Настройки > Настройки модулей > Управление структурой

В публичной части:

    Изменить страницу > Заголовок и свойства страницы

Значения свойств хранятся не в БД, а в файле.

    $APPLICATION->SetPageProperty('Свойство', 'Значение');

Вывести:

    $APPLICATION->ShowProperty('Значение');

## Название страницы

    $APPLICATION->SetPageProperty('title', 'Значение'); // Обычно для title
    $APPLICATION->SetTitle('Значение'); // Обычно для h1 если при выводе поставить false

Вывести:

    $APPLICATION->ShowTitle();
    $APPLICATION->ShowTitle(false); // покажет только от SetTitle

## Разное
Для раздела и там где не установлены свойства, данные берутся из файла `.section.php`
