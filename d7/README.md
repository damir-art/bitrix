# Битрикс D7

## Как выбирать

Фильтруем по ID  
Смотрим поля  
Выбираем по полям  
Производим действия

    // Пройтись по всей базе Контактов, везде где поля Имя и Фамилия пустое - указать значение Нет данных 
    $contacts = \Bitrix\Crm\ContactTable::getList([
      'filter' => [ 'ID' => 253 ],
      'count_total' => 1
    ]);

## Разное
Сущности CRM: https://dev.1c-bitrix.ru/user_help/service/crm/index.php

Примеры по методам getList, Add, Delete, Update и т.д. https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3570

Единые методы у всех сущностей, единые стандартизированные параметры, единые 9 событий.

getList: https://dev.1c-bitrix.ru/learning/course/index.php/lesson.php?COURSE_ID=43&LESSON_ID=5753
операции: https://dev.1c-bitrix.ru/learning/course/index.php/lesson.php?COURSE_ID=43&LESSON_ID=2244

Выборка элементов в D7:

    $comps = \Bitrix\Crm\CompanyTable::getList([
      'filter' => [ 'ID' => 1564 ],
      'select' => [ 'ID', 'TITLE', 'UF_CRM_1660115730' ],
      'count_total' => 1
    ]);

    // echo 'SELECTED: '. $comps -> getCount() . '<br />'; // Всего элементов
    // 'filter' => [ 'ID' => 1564, 'UF_CRM_1660115730' => false ],

    $i = 0;
    while ( $comp = $comps->fetch() ) {
      echo '<pre>';
      print_r($comp);
      echo '</pre>';
      if( $i == 5 ) {
        break; // для вывода 5 компаний, а не всех
      }
      $i++;
    }

    // $result = \Bitrix\Crm\CompanyTable::delete(1683); // удалить элемент

https://blog.budagov.ru/bitrix-d7-dlya-infoblokov/  
https://www.intervolga.ru/blog/projects/d7-analogi-lyubimykh-funktsiy-v-1s-bitriks/ - примеры D7 со старыми аналогами  
https://dev.1c-bitrix.ru/community/blogs/dev_bx/connection-of-css-and-js-files-in-the-component-.php - подключение стилей в шаблоне компонентов  
https://dev.1c-bitrix.ru/community/webdev/user/87386/blog/11342/ - кеширование  
https://www.youtube.com/watch?v=1_xYUQzQHj8

Объекты: https://vadim24.ru/blog/bitrix/obekty-application-context-request-server-v-bitriks-d7/

- Стили и скрипты:     `style-script.md`
- Подключение модулей: `module.md`
- Локализация:         `local.md`
- Файловая структура:  `file.md`
- Отладка:             `debug.md`
- $_GET, $_POST:       `get-post.md`
- Cookie:              `cookie.md`
- Почтовые события:    `send.php`
- Разное
  - `header-php.md`

D7 - новый API Битрикс, с использование простарнства имён. Перед началом разработки, убедитесь что в выбранном вами модуле (чье АПИ вы будете использовать) имеются классы и методы нового ядра D7.

Отличия от старого API:
- поддержка базы данных MySQL
- используется ORM
- поддержка ООП, весь код в одном месте, классе, наборе классов
- единообразный код, однаковые названия, вызовы, параметры, возрат
- поддержка простраства имён
- отсутствие глобальных переменных
- унифицированные события
- поддержка автозагрузки (autoload)

## БП
- добавить свой класс /local/yolva/classes/bp/
- создать два класса и метода:
  - EstimateHelper.php (класс также)
    - checkDeliveryPlatform(#ID_СМЕТЫ) (проверяем платформу представления)
  - TestResourcesHelper.php
    - checkDeliveryPlatform(#ID_СМЕТЫ) (проверяем платформу представления)
- оба метода возвращают массив полями Crispy и consoleT1Cloud
- значения полей true или false

TEST_RESOURCES - $code
public static function getEntityTypeIdByCode($code) получаю id смарт процесса
public static function getItemsByParameters($entityTypeId, array $parameters): array для получения элементов смартпроцессов по фильтру, можно работать как с ORM

public static function getItem($entityTypeId, $entityId) получает id смарт процесса и id элемента смарт процесса

после того как данные получили:
local/php_interface/yolva/classes/ServiceContainer/Operations/BeforeAddQuote.php

со строчки 25
getData(); массив получаем

1. метод:
EstimateHelper::checkDeliveryPlatform
- получив ID сметы, необходимо из смарт-процесса продукты сметы выбрать все элементы, с привязкой к полученно ID сметы
- перебрая полученные элементы и смотрим поле продуктовый каталог (считываем id элемента смарт-процесса продуктовый каталог)
- считываем данные по найденному ID элемента смарт-процесса продуктовый каталог
- проверяем значение в поле платформа предоставления (enumerate)
- ищем в id смете есть хотябы по одному Crispy и consoleT1Cloud (потом прерываем перебор)

2. метод:
TestResourcesHelper::checkDeliveryPlatform
- почти тоже самое только начинается с ID тестовых ресурсов
- получив ID элемента СП (смарт-процесс) тестовые ресурсы, необходимо из смарт-процесса `тестовые продукты` выбрать все элементы, с привязкой к полученно ID `тестовые ресурсы`
