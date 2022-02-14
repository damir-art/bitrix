# Работаем с заголовками
Начало см. в `04-index-php.md`.

Задавать `title` можно разными способами:
- Стандартно: `$APPLICATION->SetTitle("Заголовок страницы");`
- Через модуль `Управление структурой` создаём свойство `title`
    - на публичной странице вносим в свойства нужный заголовок
- Без изменения модуля `Управление структурой`, сразу в код внедряем:
    - `$APPLICATION->SetPageProperty("title", "Заголовок страницы");`
    - у функции `SetPageProperty()` приоритет выше чем у `SetTitle()`

Делаем разным title и h1 на странице.

    // index.php
    $APPLICATION->SetTitle("Заголовок страницы");   // устанавливает title и h1
    SetPageProperty("title", "Заголовок страницы"); // устанавливает title и h1

    // header.php
    title: $APPLICATION->ShowTitle();
    h1:    $APPLICATION->ShowTitle(false); // если false то берет значение из SetTitle

## Работаем со стилями
header.php

    $APPLICATION->ShowCSS(); // подключение стилей текущего шаблона
    $APPLICATION->SetAdditionalCSS("/...css");

## Работаем с мета и свойствами
header.php

    $APPLICATION->ShowMeta("keywords");
