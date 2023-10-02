# Шаблон
- Виды шаблонов,
- Копирование шаблона,
- Копирование в папку `local/`.

Шаблоны компонента, делятся на два вида: системные, пользовательские. От этого зависит расположение шаблона.
- системные шаблоны комопнентов располагаются в папке templates самого компонента
- пользовательские в папке шаблона сайта

## Системные шаблоны
Располагаются в папке компонента, например:
- Простой компонент `/bitrix/components/bitrix/news.list/templates`, обязательно должен быть шаблон `.default`,
- Комплексный компонент: `/bitrix/components/bitrix/news/templates/.default/bitrix/news.list/`,
  - Темы компонентов: `/bitrix/components/bitrix/news/templates/`,
  - Одна из тем: `/bitrix/components/bitrix/news/templates/.default/`,
  - В теме компонента располагается пространство имён: `/bitrix/components/bitrix/news/templates/.default/bitrix/`,
  - Шаблон простого компонента: `/bitrix/components/bitrix/news/templates/.default/bitrix/news.list/`.

## Пользовательские шаблоны
Пользовательские шаблоны которые были изменены или скопированы. Располагатся в папке `/bitrix/templates`, здесь располгаюатся:
- Все шаблоны сайта `/bitrix/templates`,
- Компоненты шаблона сайта: `/bitrix/templates/.default/components`,
- Пространство имён комопнентов: `/bitrix/templates/.default/components/bitrix`,
- Компоненты могут лежать также в самой папке `components/` без пространства имён,
- В папке local: `local/components/damir/news.list`,
  - `local/templates/.default/components/damir/news.list`.

Шаблон компонента представляет собой набор папок и файлов:
- template.php - преобразует данные подготовленные компонентом и отображает в публичной части сайта,
  - в данном файле идет работа с массивами `arParams` и `arResult`,
  - которые выводят динамическую информацию из инфоблоков.

## Изменяем шаблон комплексного компонента
Изменим шаблон системного компонента расположенного в нашем простарнстве `/local/components/damir/news` который мы вывели на странице test.php, нам скажут что это системный комопнент, и что шаблон нужно скопировать в папку:
- Прописываем название шаблона компонента: `my_shablon`,
- Копировать в шаблон сайта: По-умолчанию .default,
- Шаблон комопнента скопируется сюда: `bitrix/templates/.default/components/damir/news/my_shablon`,
  - Его можно скопировать и в папку `local` если в папке `local` есть шаблоны сайта.

У каждого шаблона сайта могут быть свои шаблоны комопнентов. `.default` применяем компонент ко всем шаблонам сайта.  
Копируем шаблон комопнента в другой шаблон сайта (например текущий) отличный от `.default`.  
Размещение: `/bitrix/templates/eshop_bootstrap_v4/components/damir/news/my_shablon_2`.  
При копировании шаблона, не забудьте установить галочку `Применить новый шаблон компонента:`.  
Редактируем шаблон: `/bitrix/templates/eshop_bootstrap_v4/components/damir/news/my_shablon_2/bitrix/news.list/.default/template.php`, сверху добавляем:

    <h1>Скопированный шаблон My_shablon_2</h1>
    <div class="news-list">

В публичной части сайта установите галочку, не использовтаь кеш.  
У `news.list` по-умолчанию шаблон установлен в `.default`, смотреть тут: `/bitrix/templates/eshop_bootstrap_v4/components/damir/news/my_shablon_2/news.php`  

## Копируем в папку local
Копируем шаблон компонента в папку local.  
Мы уже скопировали в папку local системный комплексный компонент в наше пространство: `/local/components/damir/news/`  
Создаём папку `/local/templates/.default`  
Открываем страницу test.php копируем шаблон комопнента в `/local/templates/.default`  
Назовем шаблон my_shablon_3

## Разное
В шаблоне компонента допускается немного логики, но лучше это сделать в файлах `result_modifier.php`, `component_epilog.php`.
