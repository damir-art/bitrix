# Смарт процесс
https://bx24devbook.website.yandexcloud.net/Modul_CRM/Smart_processy/Processy.html  

Работаем со смарт процессами.

## Работа с процессами
Все взаимодействие с новым API CRM и смарт-процессами в частности осуществляется посредством взаимодейтсвия с сервисами из контейнера `\Bitrix\Crm\Service\Container`. Это означает что перед выполнением любой операции необходимо сначала получить контейнер, затем достать из него нужный сервис и уже после выполнить действие.

### Создаём контейнер
Получаем контейнер для дальнейшей работы:

    use \Bitrix\Crm\Service;
    $container = Service\Container::getInstance();

### Получаем список смарт-процессов с их полями и значениями
Получаем зарегистрированные смарт-процессы (для получения списка смарт-процессов необходимо сначала получить название таблета, с которым можно работать как и с любым другим DataManager объектом).

Вывод списка всех зарегистрированных смарт-процессов, с их полями и значениями:

    use \Bitrix\Crm\Service;

    // Container
    $container = Service\Container::getInstance();

    // \Bitrix\Crm\Model\Dynamic\TypeTable
    $typeDataClass = $container->getDynamicTypeDataClass();

    // \Bitrix\Main\ORM\Query\Result
    $listDynamicTypes = $typeDataClass::getList([
        'select' => ['*']
    ])->fetchAll();

    // Вывод списка всех зарегистрированных смарт-процессов, с их полями и значениями
    echo '<pre>';
    print_r($listDynamicTypes);
    echo '</pre>';

### Создание смарт процесса
### Редактирование смарт процесса
