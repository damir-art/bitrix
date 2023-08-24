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

## При работе с событиями
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
