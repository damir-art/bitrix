# Фабрика
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/factory.php  
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/container.php  
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/field/index.php  
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/item/index.php - методы элементов смарт-процесса  
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/operation/index.php  
https://dev.1c-bitrix.ru/api_d7/bitrix/crm/crm_owner_type/identifiers.php - ID сущностей, в конце страницы есть смарт-процессы
Абстрактный класс фабрика (factory) - новая технология работы со смарт-процессами.  

Абстрактный класс фабрики, воспроизводит паттерн проектирования **Абстрактная фабрика**, где для каждого типа сущности есть своя фабрика. Реализация фабрики для каждого типа возвращает набор классов, специфичных именно для **этого типа**. Сервис фабрики является входной точкой всегда, когда необходимо работать с данными, специфичными для конкретного типа сущности.

Инстанс фабрики получается из контейнера сервисов: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/service/container.php

Здесь и далее под словом "тип" подразумевается "тип сущности в рамках CRM", такой как "Лид", "Сделка" и т.д. К ним же относятся "Смарт-процессы".

## Создание фабрики

    use Bitrix\Crm\Service;

    // Создаём фабрику (factory) смарт-процессов
    $typeid = '139'; // ID смарт-процесса
    $factory = Service\Container::getInstance()->getFactory($typeid);

## Получаем ID смарт-процесса
ORM-объект `Bitrix\Crm\Model\Dynamic\TypeTable` класс-таблет для работы с таблицей смарт-процессов.

    use Bitrix\Crm\Model\Dynamic\TypeTable;

    // Получаем идентификатор смарт-процесса "Продуктовый каталог" QUOTE_PRODUCT_CATALOG
    $query = array(
      "select" => array("ENTITY_TYPE_ID"),
      "filter" =>
      array("CODE" => 'QUOTE_PRODUCT_CATALOG')
    );
    $idSpProductCatalog = TypeTable::getList($query)->fetch()['ENTITY_TYPE_ID'];

## Поля сущности смарт-процесса
Смотреть в админке тут: http://site.loc/bitrix/admin/perfmon_table.php?lang=ru&table_name=b_crm_dynamic_type

    var_dump(\CCrmOwnerType::IsDefined($typeid)); // вернёт true если есть такая сущность

## Получаем коды полей элемента и их значения
Также выведутся и пользовательские поля.

    $fieldsCollection = $factory->getFieldsCollection();

    foreach ($fieldsCollection as $field)
    {
      echo $field->getName();
      echo ': ';
      echo $field->getTitle();
      echo '<br />';
    }

## Получаем данные пользовательских полей

    $uf = $factory->getUserFieldsInfo();

    echo '<pre>';
    print_r($uf);
    echo '</pre>';

## Получаем данные элемента смарт процесса

    // Получаем полный массив данных соответствующий карточке смарт-процесса:
    $sourceItemId = 44;
    $item = $factory->getItem($sourceItemId);

    echo '<pre>';
    print_r($item->getData());
    echo '</pre>';

## Пользовательские свойства элемента смарт-процесса

    $uf = $factory->getUserFields();

    echo '<pre>';
    print_r($uf);
    echo '</pre>';

## Получаем данные элемента смарт-процесса

    $sourceItemId = 44;// Идентификатор элемента смарт процесса
    $item = $factory->getItem($sourceItemId);

    echo '<pre>';
    print_r($item->getData()); // Получаем массив из объекта
    echo '</pre>';

## Получаем список элементов смарт-процесса

    $items = $factory->getItems([
      'select' => ['*'], // Данные каких полей нужно получить, можно указать * если нужны все
      'limit'=> 5, // Сколько элементов выбрать за запрос
    ]);

    foreach ( $items as $item ) {
      echo '<pre>';
      print_r($item->getData());
      echo '</pre>';
    }

## Получить данные элемента смарт-процесса по его ID

    $item = $factory->getItem(44);

    echo '<pre>';
    print_r($item->getData());
    echo '</pre>';

## Методы

### Общие
`getEntityTypeId()` - возвращает ID смарт процесса:

    $typeid = '139'; // ID смарт-процесса
    $factory = Service\Container::getInstance()->getFactory($typeid);
    $item = $factory->getEntityTypeId(); // 139

`getEntityName()` - возвращает строкове представление смарт процесса:

    $typeid = '139'; // ID смарт-процесса
    $factory = Service\Container::getInstance()->getFactory($typeid);
    $item = $factory->getEntityName(); // DYNAMIC_139

`getEntityAbbreviation()` - возвращает сокращенное строкове представление смарт процесса:

    $typeid = '139'; // ID смарт-процесса
    $factory = Service\Container::getInstance()->getFactory($typeid);
    $item = $factory->getEntityAbbreviation(); // T8b

`getEntityDescription()` - возвращает имя смарт процесса:

    $typeid = '139'; // ID смарт-процесса
    $factory = Service\Container::getInstance()->getFactory($typeid);
    $item = $factory->getEntityDescription(); // T8b

### Функционал типа
`isMultipleAssignedEnabled()` - возвращает true, если функционал множественных ответственных активен. На данный момент всегда возвращает false, функционал не доработан.

    $item = $factory->isMultipleAssignedEnabled();

    echo '<pre>';
    print_r($item);
    echo '<br />';
    var_dump($item); // для false которые возвращаеют пустое значение
    echo '</pre>';

`isCategoriesSupported()` - возвращает true, если тип поддерживает работу с направлениями.  
`isCategoriesEnabled()` - возвращает true, если тип поддерживает работу с направлениями и этот функционал активирован.  
`isStagesSupported()` - возвращает true, если тип поддерживает работу со стадиями.  
`isStagesEnabled()` - возвращает true, если тип поддерживает работу со стадиями и этот функционал активирован.  
`isLinkWithProductsEnabled()` - возвращает true, если для типа используется функционал привязки к товарам.  
`isBeginCloseDatesEnabled()` - возвращает true, если у типа есть поля "Дата начала" и "Дата окончания".  
`isClientEnabled()` - возвращает true, если у типа есть поле "Клиент" (привязка к контактам и компании).  
`isCrmTrackingEnabled()` - возвращает true, если тип поддерживает работу со сквозной аналитикой и utm-метками.
`isMyCompanyEnabled()` - возвращает true, если у типа есть поле "Реквизиты моей компании".	
`isDocumentGenerationEnabled()` - возвращает true, если для типа активирован функцинал печати документов.  
`isSourceEnabled()` - возвращает true, если у типа есть поля "Источник" и "Дополнительно об источнике".  
`isUseInUserfieldEnabled()` - возвращает true, если тип может быть использован в поле типа "Привязка к элементам CRM".  
`isRecyclebinEnabled()` - возвращает true, если функционал корзины активирован.  
`isAutomationEnabled()` - возвращает true, если функционал роботов и триггеров активирован.  
`isBizProcEnabled()` - возвращает true, если функционал дизайнера бизнес-процессов активирован.  
`isObserversEnabled()` - возвращает true, если у типа есть поле "Наблюдатели" с соответствующим функционалом.  
`isNewRoutingForDetailEnabled()` - возвращает true, если ссылки на детальную страницу элементов этого типа должны строиться по тому же принципу, что и ссылки на смарт-процессы.  
`isNewRoutingForListEnabled()` - возвращает true, если ссылки на список элементов этого типа должны строиться по тому же принципу, что и ссылки на смарт-процессы.  
`isNewRoutingForAutomationEnabled()` - возвращает true, если ссылки на настройку роботов и триггеров этого типа должны строиться по тому же принципу, что и ссылки на смарт-процессы.

### Поля элементов этого типа
`getFieldsInfo()` - возвращает описание полей элементов этого типа в виде массива, где ключ - код поля, а значение - описание.

    $item = $factory->getFieldsInfo();
    
    echo '<pre>';
    print_r($item);
    echo '</pre>';

`getFieldsMap('TITLE')` - возвращает карту соответствия "общих" кодов полей и кодов полей этого типа, если они отличаются от "общих" (подробнее в описании Crm\Item). Ключ - "общий" код поля, а значение - код поля этого типа.  
`getEntityFieldNameByMap()` - возвращает код поля этого типа по его "общему" коду.  
`getCommonFieldNameByMap()` - Возвращает "общий" код поля по коду поля этого типа.  
`getFieldCaption()` - метод возвращает языковое название поля по его "общему" коду. Если название не найдено, то будет возвращен сам код поля.  
`isFieldExists()` - вернет true, если поле с "общим" кодом $commonFieldName есть у элементов этого типа.  
`getFieldValueCaption()` - вернет строковое значение поля с "общим" кодом $commonFieldName и значением $fieldValue.  
`getFieldsCollection()` - вернет коллекцию полей этого типа, сюда же войдут пользовательские поля, о которых ниже.

### Пользовательские поля типа
`getUserFieldEntityId()` - возвращает идентификатор сущности для апи пользовательских полей.  

    $item = $factory->getUserFieldEntityId();

    echo '<pre>';
    print_r($item); // например CRM_7
    echo '</pre>';

`clearUserFieldsInfoCache()` - сбрасывает кеш данных о пользовательских полях типа.  
`getUserFieldsInfo()` - возвращает описание пользовательских полей в том же формате, что и метод getFieldsInfo().  
`getUserFields()` - возвращает описание пользовательских полей этого типа.  

### Направления
Направления - это что-то типа категорий.  
`getCategories()` - возвращает массив направлений этого типа.  
`createCategory()` - создает объект направления на основе данных о нем.  
`createDefaultCategoryIfNotExist()` - eсли уже существует направление по умолчанию, то метод вернет этот объект. Если направления по умолчанию нет, то...  
`getDefaultCategory()` - возвращает направление по-умолчанию, если оно есть.  

### Стадии
`getStagesEntityId()` - возвращает идентификатор ENTITY_ID для таблицы статусов. Если тип не поддерживает работу со стадиями, то вернется null.  

    $item = $factory->getStagesEntityId();

    echo '<pre>';
    print_r($item); // DYNAMIC_178_STAGE_3
    echo '</pre>';

`getStages()` - возвращает коллекцию стадий направления $categoryId. Если $categoryId не передано, но тип поддерживает работу с направлениями, то будет получен список стадий направления по умолчанию.  
`purgeStagesCache()` - очищает кеш стадий.  
`getStage()` - возвращает объект стадии по строкому идентификатору $stageId.  

### Элементы
Методы элементов: https://dev.1c-bitrix.ru/api_d7/bitrix/crm/item/index.php

`getDataClass()` - возвращает FQN класса-таблета, где хранятся элементы этого типа.  

    $item = $factory->getDataClass(); // \crm_items_7Table

`getItemByEntityObject()` - возвращает объект элемента по orm-объекту $object.  
`getItems()` - возвращает массив объектов элементов. Здесь $parameters - массив со структурой, полностью аналогичной аргументу в методе Bitrix\Main\Entity\DataManager::getList()  
`getItemsFilteredByPermissions()` - возвращает массив объектов элементов аналогично методу getList, но здесь учитываются права доступа пользователя с id $userId при выполнении действия $operation.  
`getItem()` - получить данные элемента по его ID,

Пример получаем заголовки элементов:

    use \Bitrix\Crm\Service;
    $container = Service\Container::getInstance();
    $factory = $container->getFactory( 178 );

    // Ищем все элементы
    $elements = $factory->getItems([]);

    // Ищем где в title есть буква а
    $elements = $factory->getItems([
      'filter' => [
        '%TITLE' => 'a'
      ]
    ]);

    $i = 1;
    foreach ($elements as $element) {
      echo "$i ";
      echo $element->getTitle();
      echo '<br />';
      break;
      $i++;
    }

Из смарт-процесса смета, получаем данные конкретной сметы по его ID:

    /**
    * Из смарт-процесса смета, получаем данные конкретной сметы по его ID
    * 128 - ID смарт-процесса смета
    * 7330 - ID элемента (сметы)
    */

    \Bitrix\Main\Loader::includeModule('crm');
    $spSmeta = '128'; // Идентификатор смарт-процесса
    $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($typeid);

    // Получаем данные сметы
    $smetaId = 7330;// Идентификатор сметы
    $smeta = $factory->getItem($smetaId);

    echo '<pre>';
    print_r($smeta->getData()); // Получаем массив данных сметы
    echo '</pre>';

Получив ID сметы, необходимо из смарт-процесса "Продукт сметы" выбрать все элементы, с привязкой к полученной ID сметы

    /**
    * Получив ID сметы, необходимо из смарт-процесса "Продукт сметы" выбрать все элементы, с привязкой к полученной ID сметы
    * 169 - ID смарт-процесса "Продукт сметы"
    * 7330 - ID элемента (сметы)
    */

    \Bitrix\Main\Loader::includeModule('crm');
    $spProduct = '169'; // Идентификатор смарт-процесса "Продукт сметы"
    $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($spProduct);

    // Получаем данные продукта сметы
    $smetaId = 7330;// Идентификатор сметы
    $productId = 2816;// Идентификатор "Продукта сметы"
    $product = $factory->getItem($productId);

    echo '<pre>';
    print_r($product->getData()); // Получаем массив данных сметы
    echo '</pre>';
