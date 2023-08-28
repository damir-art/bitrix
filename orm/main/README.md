# ORM
Еще одним отличием нового ядра D7 от старого ядра, является наличие ORM.
ORM - объектно реляционное отображение, это технология программирования, которая позволяет связать базы данных с концепциями объектно-ориентированных языков программирования, создавая "виртуальную объектную базу данных".

Что даёт ORM для Битрикс:
- единообразный код (вызов API), реализованных на ORM
- все таблицы с данными описываются как сущность ORM, сущностью может быть как одна таблица, так и несколько взаимосвзянных
- у сущности есть API доступ к данным таблицы

Например в старом ядре функция GetList() для инфоблока и пользователей, имела разный набор параметров, сущностей, событий. Используя ORM всё для всех стандартизировано и единообразно.

Сущность ORM имеет следующие методы:
- getList()
- add()
- update()
- delete()
- параметры передаваемые в эти методы жестко стандартизированы
- 9 событий

Сущностью ORM могут быть не только модули Битрикса, но и пользовательские модули.  
В старом ядре были умолчания по ошибкам, например при передаче параметров. В новом ядре D7 если вы передадите не корректные параметры, то сразу вызовется ошибка.

## Стандартные операции
Рассмотрим два примера получения данных на старом ядре:
- получим группы пользователей по фильтру,
- получим элемента инфоблока по фильтру.
И нам нужно только название группы и название элемента инфоблока.

### Получение данных в старом ядре
https://dev.1c-bitrix.ru/api_help/main/reference/cgroup/getlist.php  
Получение группы пользователей с `ID = 1`. Указываем сортировку $by, $order (обязательные параметры):

Возможность указать поля которые хотелось бы получить в выборке, отсутствует, получаем все поля а потом с помощью своего кода отбираем те которые нужны.

    // В случает GetList по группам пользователей, сортировка задается первыми двумя параметрами $by, $order
    $by = 'ID';        // поле для сортировки
    $order = 'sort';   // направление сортировки
    $result = CGroup::GetList($by, $order,
      array('ID'=>'1') // фильтр
    );

    echo 'Группа пользователей по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php  
Получаем элемент инфоблока с `ID = 1` (только название).  

    // Подключаем модуль инфоблока, так как метод CIBlockElement::GetList() относится к нему
    CModule::IncludeModule('iblock');

    $result = CIBlockElement::GetList(false,
      array('ID'=>'1'),
      false,
      false,
      array('NAME')
    );

    echo 'Элемент инфоблока по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

- вместо первого false можно разместить массив сортировки `array('ID'=>'ASC')`
- array('ID'=>'1') - фильтр, ищем элемент с ID = 1
- false - группировка
- false - постраничная навигация
- array('NAME') - нужно только поле name

В первом примере с группами пользователей 3,4,5 параметров не было, как функционала который бы мог это реализовать.

GetList в старом ядре, у различных модулей отличается и имеет разный набор параметров.

### getList в D7
getList() - в D7 един для всех сущностей.  
Все сущности ядра D7: https://dev.1c-bitrix.ru/api_d7/bitrix/identifiers.php

https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/getlist.php  
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5753  

Пример с пользовательской сущностью Book (таблица):

    BookTable::getList(
      array(
        'select'  => ... // имена полей, которые необходимо получить в результате
        'filter'  => ... // описание фильтра для WHERE и HAVING
        'group'   => ... // явное указание полей, по которым нужно группировать результат
        'order'   => ... // параметры сортировки
        'limit'   => ... // количество записей
        'offset'  => ... // смещение для limit
        'runtime' => ... // динамически определенные поля
      )
    );

- элементы в массиве, переданном в getList можно располагать в любом порядке,
- элементы в массиве, переданном в getList можно вообще не указывать,
- getList всегда возвращает объект `DB\Result`,

Из объекта можно получить данные с помощью метода `fetch()`:

    $result = BookTable::getList(array(
      ...
    ));

    $rows = array();
    while ($row = $result->fetch())
    {
      $rows[] = $row;
    }

Для получения сразу всех записей можно воспользоваться методом `fetchAll()`:

    $result = BookTable::getList($parameters);
    $rows = $result->fetchAll();

    // две строки выше можно объединить в одну:
    $rows = BookTable::getList($parameters)->fetchAll();

Напишем две задачи (см выше), которые написаны на старом ядре, теперь уже на D7.

https://dev.1c-bitrix.ru/api_help/main/reference/cgroup/index.php (смотрим имя сущности в D7)
Получение группы пользователей с `ID = 1`:

    $result = \Bitrix\Main\GroupTable::getList(
      array(
        'select' => array('NAME'), // Получаем имя группы пользователей с ID = 1 "Администраторы"
        'filter' => array('ID' => '1'),
      )
    );

    echo 'Группа пользователей по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

ORM сущность описана в классе `\Bitrix\Main\GroupTable`.

Получение элемента инфоблока:

    \Bitrix\Main\Loader::IncludeModule('iblock');

    $result = \Bitrix\Iblock\ElementTable::getList(
      array(
        'select' => array('NAME'),
        'filter' => array('ID' => '1'),
      )
    );

    echo 'Элемент инфоблока по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

\Bitrix\Iblock\ElementTable - класс с описанием ORM сущности.

## События
У любой сущности на ORM D7 имеетс 9 стандартных событий. Посмотрим как работают события в старом и новом ядре.

Старое ядро.  
Пример: реализуем добавление группы пользователей и зарегистрируем обработчик события.

    // Код добавления группы
    $group = new CGroup;
    $group->Add(array(
      'NAME'        => 'Имя группы',
      'DESCRIPTION' => 'Группа добавлена с использованием API старого ядра'
    ));

    // Обработчик события, сработает когда будет добавлена группа
    AddEventHandler('main', 'OnBeforeGroupAdd', 'MyOnBeforeGroupAdd');
    function MyOnBeforeGroupAdd($arFields) {
      echo '<pre>';
      var_dump($arFields); // Получаем данные события Add
      echo '</pre>';
    }

Пример: удаляем группу и получаем ID удалённой группы `<?=$_REQUEST['id']?>`.

    $group = new CGroup;
    $group->Delete($_REQUEST['id']);

В старом ядре описано событие срабатывающее перед удалением группы, но событие срабатывающее после, отсутствует. А вот у инфоблока при удалении элемента, есть событие срабатывающее после.

Новое ядро D7.  
Все сушности ядра и пользовательские, реализованные на ORM D7 имеют единые стандартизированные события.

Схема события D7:

    onBefore(Event) - можно изменить данные, можно сгенерировать ошибку
      вызов валидаторов, прерывание операции в случае налия ошибок
    on(Event) - можно сгенерировать ошибку
      выполнение SQL запроса
    onAfter(Event)

Event - одно из событий Add, Update, Delete

Добавление со стандартизованным набором входящих параметров:

    onBeforeAdd - (параметры fields)
    onAdd - (параметры fields)
    onAfterAdd - (параметры fields, primary)

Обновление:

    onBeforeUpdate - (параметры primary, fields)
    onUpdate - (параметры primary, fields)
    onAfterUpdate - (параметры primary, fields)

Удаление:

    onBeforeDelete - (параметры primary)
    onDelete - (параметры primary)
    onAfterDelete - (параметры primary)

Событие добавления группы с помощью D7:

    // Код добавления группы
    \Bitrix\Main\GroupTable::add(array(
      'NAME'        => 'Имя группы',
      'DESCRIPTION' => 'Группа добавлена с использованием API ORM D7'
    ));

    // Событие сработает когда будет добавлена группа
    $eventManager = \Bitrix\Main\EventManager::getInstance();
    $eventManager->addEventHandler(
      'main',
      '\Bitrix\Main\Group::OnBeforeAdd',
      'onBeforeAdd'
    );

    // Обработчик события происходит перед добавлением элемента "группа пользователей"
    function onBeforeAdd(\Bitrix\Main\Entity\Event $event) {
      $fields = $event->getParameter('fields');
      echo '<pre>';
      var_dump($fields); // Получаем данные события Add
      echo '</pre>';
    }

Событие удаления группы, при удалении выводим информацию об ID группы:

    $eventManager->addEventHandler(
      'main',
      '\Bitrix\Main\Group::OnAfterDelete',
      'OnAfterDelete'
    );

    function OnAfterDelete(\Bitrix\Main\Entity\Event $event) {
      $primary = $event->getParameter('primary'); // ключ удаляемого объекта
      echo '<pre>';
      var_dump($primary['ID']); // Получаем данные события Add
      echo '</pre>';
    }

## ORM отношения к ошибкам
В старом ядре ошибки и неточности разработчика прощались или код додумывался за него. В результате чего могли появляться сложно отлавливаемые ошибки.

Получение списка пользователей по фильтру на старом ядре:

    $by     = 'ID';     // поле для сортировки
    $order  = 'sort';   // направление сортировки
    $result = CUser::GetList($by, $order,
      array('ID'=>'1'), // фильтр, выбираем пользователя с ID 1
      array('FIELDS'=>array('LOGIN')) // какое поле нужно выводить
    );

    echo 'Пользователи по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

Допустим мы опечатались и вместо `array('ID'=>'1'),` ввели array('ID`s`'=>'1'), нам выведутся все пользователи которые есть на сайте, при этом код старого ядра не покажет нам ошибки.

В новое ядро D7 ничего не додумывает за пользователя и сразу выдаёт ошибку, если что-то не так: назнакомый фильтр, не передано ID, не передано значение, лишнее значение и т.п.

Получение списка пользователей по фильтру на ядре D7:

    $result = \Bitrix\Main\UserTable::getList(
      array(
        'filter' => array('ID' => '1'),
        'select' => array('LOGIN')
      )
    );

    echo 'Пользователи по фильтру:<br />';
    while($row = $result->fetch()) {
      echo '<pre>';
      var_dump($row);
      echo '</pre>';
    }

Здесь тоже опечатаемся и напишем array('ID`s`'=>'1'), ядро D7 сразу выдаст исключение.

## Создание своих сущностей
Вся прелесть ORM кроме всего описанного ранее, в стандартизации, когда все методы, события и т.д. создаются автоматически в момент описания сущности. И не важно что это за сущность Битрикса или разработчика. В любом случае вы получите API которая будет иметь 9 событий, стандартизованные методы для добавления, обновления и удаления и ряд вспомогательных которые имеют четкие стандартизованные наборы параметров.

Рассмотрим пример создания сущности на примере модуля:

modules/academy.orm
- lib/
  - orm.php - описание ORM сущности (см ниже)
  - event.php - обработчик события
- install/
  - index.php - при установке модуля создается таблица в БД и копируется компонент `install/components/academy/orm.list`

Описание ORM сущности:

    // Описание таблицы
    namespace Academy\Orm;
    use Bitrix\Main\Entity;

    // Имя файла orm.php должно быть без Table
    class OrmTable extends Entity\DataManager {

      // Имя таблицы
      public static function getTableName() {
        return 'academy_orm';
      }

      // Описание полей таблицы, метод возвращает массив объектов
      public static function getMap() {
        return array(
          new Entity\IntegerField('ID', array(
            'primary' => true, // Первичный ключ
            'autocomplete' => true // Заполняется автоматически
          )),
          new Entity\StringField('ISBN', array(
            'required' => true, // Обязательное для заполнения
          )),
          new Entity\StringField('TITLE'),
          new Entity\DateField('PUBLISH_DATE')
        );
      }
    }

После описания ORM сущности у неё сразу появятся методы и события D7 и с ней можно работать.

index.php - файл установки модуля.
