# События
- Урок из курса Битрикс: https://academy.1c-bitrix.ru/education/?COURSE_ID=66&LESSON_ID=5886  
- События в D7: https://dev.1c-bitrix.ru/learning/course/index.php/javascript?COURSE_ID=43&CHAPTER_ID=03493
- Урок из курса Битрикс D7 ORM: https://academy.1c-bitrix.ru/education/?COURSE_ID=85&LESSON_ID=7276  
- События инфоблока: https://dev.1c-bitrix.ru/api_help/iblock/events/index.php

События Битрикс позволяют расширить возможности системы.

При:
- создании элемента
- создании заказа
- добавления пользователя
-  и т.п.
в определённых точках установлены вызовы функций (обработчиков событий). Разработчик может написать произвольный код, который будет выполняться при наступлении события. В документации разработчика у каждого модуля описаны свои события.

## Пример
Повлиять на элемент инфоблока перед изменением его в админке.
- Сделаем очитску поля цены от лишних символов (запятые, пробелы, точки и т.п.).
- События:
  - до создания элемента `OnBeforeIBlockElementAdd`,
  - до изменения элемента `OnBeforeIBlockElementUpdate`.

`OnBeforeIBlockElementAdd` - событие вызывается до создания элемента и передает массив полей, нового элемента инфоблока, все данные этого элемента являются ссылками.

`OnBeforeIBlockElementUpdate` - вызывается до изменения элемента.

- Обработчики событий создаются в отдельных файлах, в папке: `local/php_interface/include/event_handlers.php`.
- Файл `event_handlers.php` хранит в себе обработчики событий.

Подключаем его в файле `local/php_interface/init.php`:

  if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/event_handlers.php"))
    include_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/event_handlers.php";

### event_handlers.php

    <?php
    // файл /bitrix/php_interface/init.php
    // регистрируем обработчик
    AddEventHandler(
      "iblock",
      "OnBeforeIBlockElementUpdate",
      Array("CIBlockHandler", "OnBeforeIBlockElementUpdateHandler")
    );

    class CIBlockHandler {
      // создаем обработчик события "OnBeforeIBlockElementUpdate"
      public static function OnBeforeIBlockElementUpdateHandler(&$arFields) {
        if(strlen( $arFields["CODE"]) <= 0 ) {
          global $APPLICATION;
          $APPLICATION->throwException("Введите символьный код. (ID:".$arFields["ID"].")");
          return false;
        }
      }
    }
    ?>

    <?php
    // файл /bitrix/php_interface/init.php
    // регистрируем обработчик
    AddEventHandler(
      "iblock",
      "OnBeforeIBlockElementUpdate",
      Array("CIBlockHandler", "OnBeforeIBlockElementUpdateHandler")
    );

    class CIBlockHandler {
      // создаем обработчик события "OnBeforeIBlockElementUpdate"
      public static function OnBeforeIBlockElementUpdateHandler(&$arFields) {
        dump($arFields, true); // выводим массив в начале страницы
      }
    }
    ?>
