# Создание модуля
Создание модуля Битрикс.  
https://vc.ru/u/803121-konstantin-kamyshin/239867-sozdanie-modulya-na-bitrix-d7

- Создадим пользовательский модуль по адресу: `local/modules/имя-партнёра.имя-модуля`
- Внутри папки `damir.custom` поместим следующие папки и файлы:
  - admin/
  - install/
  - lang/
  - lib/
  - README.md (онисание и инструкция по работе  смодулем)
  - include.php (оставим данный файл пустым, добавив лишь код "<?php")

Если не использовать точку в имени модуля то в админке он будет по адресу: `Настройки > Настройки продукта > Модули`

## Папка admin/
В этой директории создаём файл `menu.php`:

    <?php
    // Подключаем класс и файлы локализации
    use Bitrix\Main\Localization\Loc;
    Loc::loadMessages(__FILE__);
  
    // Добавляем пункт меню для нашего модуля
    $menu = array(
      array(
        'parent_menu' => 'global_menu_content', // определяем место меню, в данном случае оно находится в главном меню
        'sort' => 400, // сортировка, в каком месте будет находится наш пункт
        'text' => Loc::getMessage('MYMODULE_MENU_TITLE'), // описание из файла локализации
        'title' => Loc::getMessage('MYMODULE_MENU_TITLE'), // название из файла локализации
        'url' => 'mymodule_index.php', // ссылка на страницу из меню
        'items_id' => 'menu_references', // описание подпункта, то же, что и ранее, либо другое, можно вставить сколько угодно пунктов меню
        'items' => array(
          array(
            'text' => Loc::getMessage('MYMODULE_SUBMENU_TITLE'),
            'url' => 'mymodule_index.php?lang=' . LANGUAGE_ID,
            'more_url' => array('mymodule_index.php?lang=' . LANGUAGE_ID),
            'title' => Loc::getMessage('MYMODULE_SUBMENU_TITLE'),
          ),
        ),
      ),
    );

    return $menu;

## Папка install/
В этой директории создаём файл `index.php`:

    <?php
    // Подключаем основные классы для работы с модулем
    use Bitrix\Main\Application;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Entity\Base;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\ModuleManager;

    // В данном модуле создадим адресную книгу,
    // и здесь мы подключаем класс, который создаст нам эту таблицу
    use Module\Adress\AdressTable;
    Loc::loadMessages(__FILE__);
    
    // В названии класса пишем название директории нашего модуля,
    // только вместо точки ставим нижнее подчеркивание
    class damir_custom extends CModule {
      public function __construct() {
        $arModuleVersion = array();
        
        // Подключаем версию модуля (файл будет следующим в списке)
        include __DIR__ . '/version.php';
        // Присваиваем свойствам класса переменные из нашего файла
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
          $this->MODULE_VERSION = $arModuleVersion['VERSION'];
          $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        // Пишем название нашего модуля как и директории
        $this->MODULE_ID = 'damir_custom';
        // Название модуля
        $this->MODULE_NAME = Loc::getMessage('MYMODULE_MODULE_NAME');
        // Описание модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage('MYMODULE_MODULE_DESCRIPTION');
        // Используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
        $this->MODULE_GROUP_RIGHTS = 'N';
        // Название компании партнера предоставляющей модуль
        $this->PARTNER_NAME = Loc::getMessage('MYMODULE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://вашсайт'; // адрес вашего сайта
      }

      // Здесь мы описываем все, что делаем до инсталляции модуля,
      // мы добавляем наш модуль в регистр и вызываем метод создания таблицы
      public function doInstall() {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
      }

      // Вызываем метод удаления таблицы и удаляем модуль из регистра
      public function doUninstall() {
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
      }

      // Вызываем метод создания таблицы из выше подключенного класса
      public function installDB() {
        if (Loader::includeModule($this->MODULE_ID)) {
          AdressTable::getEntity()->createDbTable();
        }
      }

      // Вызываем метод удаления таблицы, если она существует
      public function uninstallDB() {
        if (Loader::includeModule($this->MODULE_ID)) {
          if (Application::getConnection()->isTableExists(Base::getInstance('\Module\Adress\AdressTable')->getDBTableName())) {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(AdressTable::getTableName());
          }
        }
      }
    }

Файл `install.php`:

    <?php
    // Версия релиза модуля и дата релиза
    $arModuleVersion = array(
      'VERSION' => '0.0.1',
      'VERSION_DATE' => '2020-10-11'
    );

## Папка lang
В папке `lang/` создаем папку `ru/` для русского языка:
- в папке `ru/` ссоздаём папки `admin`, `install`, `lib`

Файл `admin/menu.php`:

    <?php
    $MESS['MYMODULE_MENU_TITLE'] = 'Название компании';
    $MESS['MYMODULE_SUBMENU_TITLE'] = 'Тестовый модуль';

Файл `install/index.php`:

    <?php
    $MESS['MYMODULE_MODULE_NAME'] = 'название модуля';
    $MESS['MYMODULE_MODULE_DESCRIPTION'] = 'описание модуля';
    $MESS['MYMODULE_MODULE_PARTNER_NAME'] = 'название партнера';

Файл `lib/adress.php` (для следующего файла):

    <?php
    $MESS['MYMODULE_NAME'] = 'Название';
    $MESS['MYMODULE_NAME_DEFAULT_VALUE'] = 'Безымянный элемент';
    $MESS['MYMODULE_ADRESS'] = 'Адрес';
    $MESS['MYMODULE_ADRESS_DEFAULT_VALUE'] = 'Нет адреса';

## Папка lib
Файл `lib/adress.php` (файл, который формирует таблицу для работы с ней):

    <?php
    namespace Module\Adress;

    use Bitrix\Main\Entity\DataManager;
    use Bitrix\Main\Entity\IntegerField;
    use Bitrix\Main\Entity\StringField;
    use Bitrix\Main\Entity\DatetimeField;
    use Bitrix\Main\Entity\Validator;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\Type;
    Loc::loadMessages(__FILE__);

    class AdressTable extends DataManager {
      // Название таблицы
      public static function getTableName() {
        return 'adressbook';
      }
      // Создаем поля таблицы
      public static function getMap() {
        return array(
          new IntegerField('ID', array(
            'autocomplete' => true,
            'primary' => true
          )), // autocomplite с первичным ключом
          new StringField('NAME', array(
            'required' => true,
            'title' => Loc::getMessage('MYMODULE_NAME'),
            'default_value' => function () {
              return Loc::getMessage('MYMODULE_NAME_DEFAULT_VALUE');
            },
            'validation' => function () {
              return array(
                new Validator\Length(null, 255),
              );
            },
          )), // обязательная строка с default значением и длиной не более 255 символов
          new StringField('ADRESS', array(
            'required' => false,
            'title' => Loc::getMessage('MYMODULE_ADRESS'),
            'default_value' => function () {
              return Loc::getMessage('MYMODULE_ADRESS_DEFAULT_VALUE');
            },
            'validation' => function () {
              return array(
                new Validator\Length(null, 255),
              );
            }
          )), // обязательная строка с default значением  и длиной не более 255 символов
          new DatetimeField('UPDATED_AT',array(
            'required' => true)), // обязательное поле даты
          new DatetimeField('CREATED_AT',array(
            'required' => true)), // обязательное поле даты
        );
      }
    }

Данный модуль - это каркас модуля, у него даже нет интерфейса для работы с пользователем, он не создает компонента, только создает таблицу, в следующих статьях мы создадим модуль создающий компонент по работе с таблицей через rest-api, а также модуль, который будет содержать интерфейс для работы с пользователем включающий возможность редактирования, обновления, создания и удаления записей в таблице.

Разное:
- Вероятно, массив `$arModuleVersion` все же должен лежать по пути `module.name/install/version.php`, а не `module.name/install/install.php`
