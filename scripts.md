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
      'local/log.php'             // путь куда записать
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

## Чтобы не завис скрипт при запуске
Скрипт помещаем внутри обычной страницы: http://code.matveevs.ru/cms/bitrix/bitrix-%D1%83%D0%B4%D0%B0%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%8D%D0%BB%D0%B5%D0%BC%D0%B5%D0%BD%D1%82%D0%BE%D0%B2-%D0%B8%D0%B7-%D0%B8%D0%BD%D1%84%D0%BE%D0%B1%D0%BB%D0%BE%D0%BA%D0%B0/

    // устанавливаем лимит выполнения скрипта 120 сек
    set_time_limit(120);
    // включаем вывод ошибочек
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    // включаем замер исполнения скрипта
    $startTime = microtime(true);
    
    // логирование pid start
    
    $pid     = getmypid();
    $file    = fopen('../logs/detelePid.txt', 'a+');
    $message = "pid: [$pid] DELETE : " . date("d.m.Y H:i:s") . PHP_EOL;
    
    fwrite($file, $message);
    fclose($file);
    
    // логирование pid end
    
    // подключаем prolog bitrix 
    require $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';

## Автозагрузка
Пример автозагрузки классов, допустим класс лежит в файле `asd.metrika/lib/counters.php`:

    namespace Asd\Metrika;
    class CountersTable extends Entity\DataManager {
      public static function update() {
      
      }
    }

Класс `CountersTable` располагается в папке `lib/` и принадлежит модулю `asd.metrika`, к нему после подключения указанного модуля можно обращаться так:

    \Asd\Metrika\CountersTable::update();


