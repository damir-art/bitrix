<?php
// Загрузка используемых классов
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__); // Загрузка языковых файлов

// Класс реализующий установку и удаление модуля
// Имя класса дожно быть как у папки модуля, только вместо точки нужно использовать подчеркивание _
// Даже если класс пустой, модуль уже должен отобразиться в нашей админке ввиде двух скобок ()
class damir_buttonup extends CModule {

  // Конструктор класса, сюда помещаем описание модуля
  public function __construct() {
    if( file_exists(__DIR__."/version.php") ) {
      $arModuleVersion = array();
      include_once(__DIR__."/version.php");
      $this->MODULE_ID           = str_replace("_", ".", get_class($this));

      // \Bitrix\Main\Diag\Debug::writeToFile(
      //   $this->MODULE_ID,      // что записать, переменную или массив
      //   ' ВЫВОД ',     // коментарий к записи, к переменной или массиву, можно поставить дату
      //   'local/log.php' // путь куда записать
      // );

      $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
      $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
      // Загружает из lang/ru/install/index.php
      $this->MODULE_NAME         = Loc::getMessage("DAMIR_BUTTONUP_NAME");
      $this->MODULE_DESCRIPTION  = Loc::getMessage("DAMIR_BUTTONUP_DESCRIPTION");
      $this->PARTNER_NAME        = Loc::getMessage("DAMIR_BUTTONUP_PARTNER_NAME");
      $this->PARTNER_URI         = Loc::getMessage("DAMIR_BUTTONUP_PARTNER_URI");
    }
    return false;
  }

  // Метод отрабатывает при установке модуля
  public function DoInstall() {
    global $APPLICATION;
    // Проверяем поддерживает ли Битрикс D7
    if( CheckVersion(ModuleManager::getVersion("main"), "14.00.00") ) {
      // Создаем таблицы баз данных, необходимые для работы модуля
      $this->InstallDB();
      // Регистрируем обработчики событий
      $this->InstallEvents();
      // Копируем файлы, необходимые для работы модуля
      $this->InstallFiles();
      // Регистрируем модуль в системе
      ModuleManager::registerModule($this->MODULE_ID);
    } else {
      $APPLICATION->ThrowException(Loc::getMessage("DAMIR_BUTTONUP_INSTALL_ERROR_VERSION"));
    }
    // Подключаем скрипт с административным прологом и эпилогом
    $APPLICATION->IncludeAdminFile(
      Loc::getMessage("DAMIR_BUTTONUP_INSTALL_TITLE"),
      __DIR__."/step.php"
    );
    return false;
  }

  // Копируем наши скрипты и стили в систему.
  public function InstallFiles() {
    CopyDirFiles(
      __DIR__."/assets/scripts",
      Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID . "/",
      true,
      true
    );
    CopyDirFiles(
      __DIR__."/assets/styles",
      Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID . "/",
      true,
      true
    );
    return false;
  }

  // Метод для создания таблиц модуля (пустой, потому что не работаем с БД)
  public function InstallDB() {
    return false;
  }

  // Регистрируем событие OnBeforeEndBufferContent.
  // Перед тем, как страница будет отрисоваться, мы добавим свой HTML код,
  // в котором сохраним настройки для нашей кнопки.
  // Этот весь механизм далее напишем в файле Main.php.
  public function InstallEvents() {
    // $eventManager = \Bitrix\Main\EventManager::getInstance();
    // $eventManager->registerEventHandler(
    //   'main',
    //   'OnBeforeEndBufferContent',
    //   'damir.buttonup',
    //   'Damir\Buttonup\Main',
    //   'appendScriptsToPage'
    // );
    EventManager::getInstance()->registerEventHandler(
      "main",
      "OnBeforeEndBufferContent",
      $this->MODULE_ID,
      "Damir\Buttonup\Main",
      "appendScriptsToPage"
    );
    //  RegisterModuleDependences(
    //    "main",
    //    "OnBeforeEndBufferContent",
    //    $this->MODULE_ID,
    //    "Damir\\Buttonup\\Main",
    //    "appendScriptsToPage"
    //  );
   return true;
  }

  // Метод отрабатывает при удалении модуля
  // Вызываем uninstall методы, удаляем регистрационную запись о модуле из базы данных
  // и подключаем файл unstep.php
  public function DoUninstall() {
    global $APPLICATION;
    $this->UnInstallFiles();
    $this->UnInstallDB();
    $this->UnInstallEvents();
    ModuleManager::unRegisterModule($this->MODULE_ID);
    $APPLICATION->IncludeAdminFile(
      Loc::getMessage("DAMIR_BUTTONUP_UNINSTALL_TITLE"),
      __DIR__."/unstep.php"
    );
    return false;
  }

  // Удаляем добавленные скрипты и стили из системы
  public function UnInstallFiles(){
    Directory::deleteDirectory( Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID );
    Directory::deleteDirectory( Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID );
    return false;
  }

  // Удаляем из базы, настройки нашего модуля
  public function UnInstallDB() {
    Option::delete( $this->MODULE_ID );
    return false;
  }

  // Удаляем регистрационную запись обработчика события OnBeforeEndBufferContent
  public function UnInstallEvents() {
    EventManager::getInstance()->unRegisterEventHandler(
      "main",
      "OnBeforeEndBufferContent",
      $this->MODULE_ID,
      "Damir\Buttonup\Main",
      "appendScriptsToPage"
    );
    return false;
  }

}
