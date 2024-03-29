# .description.php
Файл .description.php предназначен для того, чтобы отобразить описание в визуальном дереве редактора.

Данный файл может отсутсвовать, он никак не взаимодействует с `component.php`.  
Копируем компонент `/bitrix/components/bitrix/news` в `/local/components/damir/news`  
Создаём на сайте тестовую страницу для работы с компонентом.  
Открываем визуальный редактор.  
Обновляем список компонентов справа.  
Перетаскиваем компонент Новости на страницу.  
В настройках компонента сверху должно быть `damir:news`.  
Шаблон по дефолту, тип инфоблока Новости, инфоблок Новости, Сохранить.  

Открываем визуальный редактор с компонентами справа.  
Открываем код файла `.description.php`  
Массив `$arComponentDescription` описывает содержание компонента и порядок его отображения в виртуальном дереве редактора, содержит следующие ключи:
- NAME - название компонента
- DESCRIPTION - описание компонента (появляется при наведении на восклицательный знак в настройках компонента)
- ICON - путь к иконке, от корня компонента
- COMPLEX - является ли компонент комплексным
- PATH - параметры расположения в визуальном дереве редактора
  - ID - код главной ветки дерева (Контент)
  - CHILD - дочерняя ветка
    - ID - код дочерней ветки дерева (Статьи и новости)
    - NAME - наименование ветки
    - SORT - сортировка
    - CHILD - дочерняя ветка
      - ID - код ветки дерева (Новости)

Максимум может быть три уровня веток.  
Зарезервированные слова `content` Контент, `service` Сервисы, `communication` Общение, `e-store` Магазин, `utility` Служебные использовать нельзя.

При копировании системных компонентов происходит его дублирование в дереве визуального редактора.  
Создадим свой раздел в визуальном редакторе для пользовательских компонентов.  
В файле `.description.php` меняем `"ID" => "content"` на `"ID" => "damir_content"`.  
Наш скопированный компонент news переместится туда.  

Меняем имя корневого раздела с нашими компонентами с damir_content на Компоненты Damir, добавив ключ NAME в массив PATH:

    "PATH" => array(
      "ID" => "damir_content",
      "NAME" => "Компоненты Damir",

Также меняем названия дочерних веток, самого компонента и его описания:

    $arComponentDescription = array(
      "NAME" => "Тестовый компонент Damir",
      "DESCRIPTION" => "Описание тестового компонента Damir",
      "ICON" => "/images/news_all.gif",
      "COMPLEX" => "Y",
      "PATH" => array(
        "ID" => "damir_content",
        "NAME" => "Компоненты Damir",
        "CHILD" => array(
          "ID" => "news",
          "NAME" => "Новости Damir",
          "SORT" => 100,
          "CHILD" => array(
            "ID" => "news_cmpx",
          ),
        ),
      ),
    );

## Расширяем типовой функционал компонента
В режиме правки, при наведении в публичной части на компонент появляются кнопки работы с компонентом.  
Создадим кнопку которая будет вести себя как ссылка (или выполнять действия на JavaScrtipt) ведущая в корень компонента.  
Для этого нам понадобится ключ `AREA_BUTTONS`, который является массивов и в качестве его элементов выступают тоже массивы (описывающие отдельные кнопки).  
Массив описывающий кнопку состоит из трех ключей: `URL` - ссылка (или действие которое нужно совершить по нажатию на кнопку), `SRC` - путь к иконке, `TITLE` - название кнопки.

- `URL` - путь к корню компонента: http://bx.loc/bitrix/admin/fileman_admin.php?PAGEN_1=1&SIZEN_1=20&lang=ru&site=s1&path=%2Flocal%2Fcomponents%2Fdamir%2Fnews&show_perms_for=0
- `SRC` - помещаем иконку в `/local/components/damir/news/images`, указываем путь: `/images/code.png`
- `TITLE` - Название кнопки

Размещаем после массива `PATH`:

    'AREA_BUTTONS' => array(
      array(
        'URL' => 'http://bx.loc/bitrix/admin/fileman_admin.php?PAGEN_1=1&SIZEN_1=20&lang=ru&site=s1&path=%2Flocal%2Fcomponents%2Fdamir%2Fnews&show_perms_for=0',
        'SRC' => '/images/code.png',
        'TITLE' => 'Перейти к корню компонента'
      )
    )

Создаём кнопку на скрипт JS (массив помещаем внутрь `AREA_BUTTONS`):

    array(
      'URL' => 'javascript:alert(" Hello Button ")',
      'SRC' => '/images/code.png',
      'TITLE' => 'Код на JS'
    )

Сброс кеша у комплексного компонента. Создадим кнопку сброса кеша у комплексного компонента, по-умолчанию этой кнопки у комплексного нет. После ключа `AREA_BUTTONS` добавляем:

    'CACHE_PATH' => 'Y'

По клику по треугольнку, рядом с кнопкой отключить компонент, появится кнопка `Обновить кеш компонента`.

Итоговый код файла `.description.php`:

    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

    $arComponentDescription = array(
      "NAME" => "Тестовый компонент Damir",
      "DESCRIPTION" => "Описание тестового компонента Damir",
      "ICON" => "/images/news_all.gif",
      "COMPLEX" => "Y",
      "PATH" => array(
        "ID" => "damir_content",
        "NAME" => "Компоненты Damir",
        "CHILD" => array(
          "ID" => "news",
          "NAME" => "Новости Damir",
          "SORT" => 100,
          "CHILD" => array(
            "ID" => "news_cmpx",
          ),
        ),
      ),
      'AREA_BUTTONS' => array(
        array(
          'URL' => 'http://bx.loc/bitrix/admin/fileman_admin.php?PAGEN_1=1&SIZEN_1=20&lang=ru&site=s1&path=%2Flocal%2Fcomponents%2Fdamir%2Fnews&show_perms_for=0',
          'SRC' => '/images/code.png',
          'TITLE' => 'Перейти к корню компонента'
        ),
        array(
          'URL' => 'javascript:alert(" Hello Button ")',
          'SRC' => '/images/code.png',
          'TITLE' => 'Код на JS'
        )
      ),
      'CACHE_PATH' => 'Y'
    );
