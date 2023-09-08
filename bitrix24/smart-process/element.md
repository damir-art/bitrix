# Элементы смарт-процессов
https://bx24devbook.website.yandexcloud.net/Modul_CRM/Smart_processy/Elementy.html

Работаем с элементами смарт процессов.

Взаимодействие с элементами смарт-процессов осуществляется через фабрики `\Bitrix\Crm\Service\Factory`, получать которые необходимо из контейнера `\Bitrix\Crm\Service\Container`.

Это означает что перед выполнением любой операции, необходимо:
- сначала получить контейнер,
- затем получить необходимую фабрику
- и уже после выполнить действие.

Самый важный идентификатор смарт-процесса, это `ENTITY_TYPE_ID`, например 178:

## Получаем фабрику элементов смарт-процеса

    use \Bitrix\Crm\Service;

    /**
    * @var Service\Container
    */
    $container = Service\Container::getInstance();

    /**
    * @var Service\Factory\Dynamic
    */
    $factory = $container->getFactory( 178 );

## Получаем список полей элемента смарт-процесса
Методы для работы с полями элементов смарт-процесса: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/field/index.php

Для получения списка полей смарт-процесса использутся метод `getFieldsCollection()`, он возвращает экземпляр `\Bitrix\Crm\Field\Collection` с описанием всех полей элемента смарт-процесса:

    use \Bitrix\Crm\Service;

    $container = Service\Container::getInstance();
    $factory = $container->getFactory( 178 );

    $fieldsCollection = $factory->getFieldsCollection();

    foreach ($fieldsCollection as $field) {
      // @var $field Bitrix\Crm\Field
      echo '<pre>';
      print_r($field->getName());
      echo ': ';
      print_r($field->getTitle());
      echo '<br />';
      echo '</pre>';
    }

## Выборка (поиск) элементов смарт-процесса
Для поиска элементов, в каждой фабрике существуют методы `getItems` и `getItemsFilteredByPermissions`, которые отождествяют собой прямой доступ к элементам и доступ с учетом прав пользователя.

Метод `getItems()`, по параметрам аналогичен ORM методу `getList()`, однако вместо массива массивов, возвращает массив объектов `Bitrix\Crm\Item\Dynamic`.

Найдем все элементы исходного смарт-процесса, которые содержат букву "a":

    $elementsWithA = $factory->getItems([
      'filter' => [
        '%TITLE' => 'a'
      ]
    ]);

    foreach ($elementsWithA as $element) {
      echo "<pre>";
      print_r($element->getTitle());
      echo "</pre>";
    }

## Операции (низкоуровневые)

### Создание элемента
Оба совершаемых действия (созданию и обновлению) над элементом в объектном подходе обеспечивается методом `save()`, который вызывается на объекте-наследнике `Bitrix\Crm\Item`.

Вариант с низкоуровневым созданием элемента смарт-процесса:

    $item = $factory->getItemByEntityObject(
      $factory->getDataClass()::createObject();
    );

    $item->setTitle('My first element');
    // Настройте другие поля с помощью методов $item->set*

    $saveResult = $item->save();

    if ( $saveResult->isSuccess() ) {
      /**
      * Success result
      * $item->getId();
      */
    } else {
      /**
      * Some errors
      * Get error with:
      * $saveResult->getErrors();
      *
      * Get error messages:
      * $saveResult->getErrorMessages();
      */
    }

Почему данный вариант не подходит для использования разработчиком? Это самое низкоуровненое сохранение элемента, в котором не происходит следующих действий:

- Установка значений по-умолчанию,
- Проверки прав,
- Установки связей,
- Отправки push уведомлений,
- Запуска бизнес-процессов и автоматизаций,
- Перерасчета прав,
- Обновление поискового индекса,
- Учета статистики,
- Формирования timeline.

### Создание элемента (более продвинутый способ)

    $initialFields = [
      'TITLE' => 'My first element'
    ];

    $item = $factory->createItem($initialFields);
    // Setup other fields with $item->set* methods

    $saveResult = $item->save();

    if ( $saveResult->isSuccess() ) {
      /**
      * Success result
      * $item->getId();
      */
    }
    else {
      /**
      * Some errors
      * Get error with:
      * $saveResult->getErrors();
      *
      * Get error messages:
      * $saveResult->getErrorMessages();
      */
    }

### Рекомендуемый вариант с использованием операций
Использование операций это рекомендуемый способ по сохранению элемента в полном объеме. Указанная операция создает элемент `getAddOperation()`. Для обновления элемента нужно использовать `getUpdateOperation()`.

    $initialFields = [
      'TITLE' => 'My first element'
    ];

    $item = $factory->createItem($initialFields);
    // Setup other fields with $item->set* methods

    $saveOperation = $factory->getAddOperation($item);
    $operationResult = $saveOperation->launch();

    if ( $operationResult->isSuccess() ) {
      /**
      * Operation success!
      */
    }
    else {
      /**
      * Some errors
      * Get error with:
      * $operationResult->getErrors();
      *
      * Get error messages:
      * $operationResult->getErrorMessages();
      */
    }

### Удаление элемента
Существует несколько способов удаления элемента: низкоуровневый delete и операция по удалению элемента.

Низкоуровневый:

    /**
    * @item instanceof Bitrix\Crm\Item
    */

    $deleteResult = $item->delete();

    if ( $deleteResult->isSuccess() ) {
      /**
      * Item deleted
      */
    } else {
      /**
      * Some errors
      * Get error with:
      * $deleteResult->getErrors();
      *
      * Get error messages:
      * $deleteResult->getErrorMessages();
      */
    }

Почему данный вариант не подходит для использования разработчиком? Не смотря на то что элемент смарт-процесса фактически удаляется, согласованность данных не обеспечивается, а это значит что не происходит следующих действий:

- Очистки товарных позиций,
- Очистки записей о правах,
- Очистка связей с событиями,
- Очистки действий,
- Очистки связей с чатами,
- Очистки счетчиков и т.д.

Рекомендуемый способ (операции):

    /**
    * @item instanceof Bitrix\Crm\Item
    */

    $deleteOperation = $factory->getDeleteOperation($item);
    $operationResult = $deleteOperation->launch();

    if ( $operationResult->isSuccess() ) {
      /**
      * Operation success!
      */
    } else {
      /**
      * Some errors
      * Get error with:
      * $operationResult->getErrors();
      *
      * Get error messages:
      * $operationResult->getErrorMessages();
      */
    }
