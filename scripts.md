# Скрипты
Различные полезные скрипты:

    global $USER;
    if ($USER->IsAdmin()) {
      // Код выполнится только для администратора
    }

    global $USER;
    if ($USER->IsAuthorized()) {
      // Код выполнится только для авторизованных пользователей
    }

## Работаем в PHP консоли
Получаем доступ к методу класса модуля:

    use Yolva\Local\EventHandlers\Main;
    $a = Main::hello();

    echo '<pre>';
    print_r($a);
    echo '</pre>';

    // Сам класс
    class Main {
      public static function hello() {
        echo 'hello';
      }
      public static function by() {
        return self::hello();
      }
    }

## Дебаггинг при работе с событиями
https://dev.1c-bitrix.ru/api_d7/bitrix/main/diag/debug/index.php  
Debug класс для проведения отладки ajax-запросов, крон-файлов и решения других подобных задач.

При работе с событиями дебаг и отладку нужно проводить в файлах, а не выводить на странице через echo:

    /Bitrix/Main/Diag/Debug::writeToFile

    \Bitrix\Main\Diag\Debug::writeToFile(
      $arFields,                  // что записать, переменную или массив
      __LINE__.' $arFields[] = ', // коментарий к записи, к переменной или массиву, можно поставить дату
      'local/log.log'             // путь куда записать
    );

dumpToFile для объектов

## Как определить средствами php где определена функция?

    $ref = new ReflectionFunction('bx_mail');
    echo $ref->getFileName().' определена в ' .$ref->getStartLine();

## Исключения

  if(empty($innRQ)) {
    $oException->AddMessage(
      [
        "text" => "Ошибка создания компании. Не заполнен реквизит: ИНН.",
        "id" => false
      ]
    );
    $bErr = true;
  }

  if ($bErr) {
    $arFields['RESULT_MESSAGE'] = $oException->GetString();
    $APPLICATION->throwException($oException);
    return false;
  }

## Если зависают кнопки
Смотреть ответ сервера по сети. Настройки CRM бизнес процессы.
