# Операции
https://bx24devbook.website.yandexcloud.net/Modul_CRM/Smart_processy/Operacii.html  

Операция - действие или их совокупность для достижения какой-либо цели.

Рассуждая об элементах смарт-процессов в CRM можно запросто запутаться в понятиях и их взаимосвязях. Поначалу неопытные разработчики принимают запись в базу каких-либо значений за совершение действий, однако это вовсе не так. Если вы записали в какую-то таблицу какие-то значения, то это совершенно не значит что те же действия будут выполнены и в стандартных компонентах. Нет-нет, определенно запись в базу данных там тоже будет, но это будет являться лишь вершиной айсберга.

Представим себе элемент смарт-процесса, который мы хотим добавить. Добавление это действие которое мы хотим совершить и запись в базу лишь часть действительно большого процесса - добавления элемента. Здесь мы можем попасть в логическую ловушку и подумать что процесс добавление элемента это и есть фактическое добавление записи в базу данных однако не стоит забыть что в нем так же участвуют обработчики событий, проверки прав, нормализация данных, поисковая индексация и другие важные этапы, в том числе запуск бизнес-процессов запускаемых при добавлении элемента.

Термин "Операция" наиболее емко описывает необходимый набор действий для достижений конкретной цели. Например "Операция добавления элемента" описывается набор действий который нужно выполнить над элементом для его полного и корректного сохранения.

## Порядок действий в операции
В общем случае, процесс выполняется следующим образом:
- Проверка тарифных ограничений,
- Проверка прав доступа к элементу,
- Проверка запущенных БП (актуально для удаления),
- Обработка доп. логик полей перед операцией,
- Проверка заполненных полей (на предмет обязательности),
- Если элемент изменен, запуск Действий до сохранения,
- Если элемент не изменен - завершение операции,
- Обработка доп. логик полей после операции,
- Обновление прав,
- Обновление поискового индекса,
- Сохранение записи в историю и timeline,
- Запуск Действий после сохранения,
- Если применимы стадии - отправка push сообщения,
- Запуск бизнес-процессов,
- Запуск автоматизации.

Порядок выполнения действий строго определен, однако в зависимости от конкретной операции, некоторые действия могут быть пропущены (вернуть базовый \Bitrix\Main\Result). 

На текущий момент существует несколько зарегистрированных и поддерживаемых операций:

- Добавление `\Bitrix\Crm\Service\Operation\Add`
- Обновление `\Bitrix\Crm\Service\Operation\Update`
- Удаление `\Bitrix\Crm\Service\Operation\Delete`
- Копирование `\Bitrix\Crm\Service\Operation\Copy`
- Конвертация `\Bitrix\Crm\Service\Operation\Conversion`

## Выполнение операции
Выполнение операций является очень простым действием: необходимо получить операцию, сконфигурировать ее (если необходимо, будет рассмотрено ниже) и запустить выполнение.

Несмотря на то что существует 2 возможности получить операцию (прямое создание объекта и с использованием фабрики), в этой статье будет рассмотрено исключительно получение операции через `фабрику`, так как этот способ является единственно верным решением для работы.

Под каждую операцию в абстрактном классе фабрике забронированы следующие имена:
- `getAddOperation` - для операции добавления,
- `getUpdateOperation` - для операции обновления,
- `getDeleteOperation` - для операции удаления,
- `getConversionOperation` - для операции конвертации,
- `getCopyOperation` - для операции копирования.

Пример: есть существующий смарт-процесс (Entity type id: 140), и некоторый элемент (ID:7) в нем. Нам необходимо изменить название (TITLE) у этого элемента на Test.

    use \Bitrix\Main;
    use \Bitrix\Crm\Service;

    Main\Loader::requireModule('crm');
    $entityTypeId = 140;
    $elementId = 7;

    $factory = Service\Container::getInstance()->getFactory($entityTypeId);

    $item = $factory->getItem($elementId);
    $item->setTitle('Test');

    // Step 1: get opetation
    $operation = $factory->getUpdateOperation($item);

    // Step 2: config operation (optional)
    // .. skipped ..

    // Step 3: launch operation
    $operationResult = $operation->launch();

    if ( $operationResult->isSuccess() ) {
      /**
      * Operation success
      */
    } else {
      /**
      * Operation failed with error
      *
      * @operationResult->getErrors();
      * @operationResult->getErrorMessages();
      */
    }

## Конфигурация операций
Каждый смарт-процесс и сущность CRM уникальная, как и каждая выполняемая операция над элементом. Если вы запускаете импорт элементов из консоли нет необходимости проверять права текущего авторизованного пользователя (его там нет), а если это массовое однотипное действие (например замена каких-то полей), не нужно запускать бизнес-процессы и автоматизации.

Разработчики заложили возможности гибко настраивать каждую операцию.

`disableAllChecks` - выключает все проверки при выполнении операций. Аналогичен вызовам `disableCheckWorkflows`, `disableCheckAccess`, `disableCheckFields`, `disableCheckRequiredUserFields`.

    Действие                                                Включение                       Выключение                      Проверка
    Проверка прав доступа к элементу                        enableCheckAccess               disableCheckAccess              isCheckAccessEnabled
    Проверка запущенных БП                                  enableCheckWorkflows            disableCheckWorkflows           isCheckWorkflowsEnabled
    Обработка бизнес-логик полей (до и после)               enableFieldProcession           disableFieldProcession          isFieldProcessionEnabled
    Проверка заполненных полей (на предмет обязательности)  enableCheckFields               disableCheckFields              isCheckFieldsEnabled
    Валидация польз.полей                                   enableCheckRequiredUserFields   disableCheckRequiredUserFields  isCheckRequiredUserFields
    Если элемент изменен, запуск Действий до сохранения     enableBeforeSaveActions         disableBeforeSaveActions        isBeforeSaveActionsEnabled
    Сохранение записи в историю и timeline                  enableSaveToHistory             disableSaveToHistory            isSaveToHistoryEnabled
    Запуск Действий после сохранения                        enableAfterSaveActions          disableAfterSaveActions         isAfterSaveActionsEnabled
    Запуск бизнес-процессов                                 enableBizProc                   disableBizProc                  isBizProcEnabled
    Запуск автоматизации                                    enableAutomation                disableAutomation               isAutomationEnabled

Пример: повторим пример выше, но с определенными настройками: выключим проверки бизнес-процессов и стандартных полей:

    use \Bitrix\Main;
    use \Bitrix\Crm\Service;

    Main\Loader::requireModule('crm');
    $entityTypeId = 140;
    $elementId = 7;
    $factory = Service\Container::getInstance()->getFactory($entityTypeId);

    $item = $factory->getItem($elementId);
    $item->setTitle('Test');

    // Step 1: get opetation
    $operation = $factory->getUpdateOperation($item);

    // Step 2: config operation (optional)
    $operation
      ->disableCheckWorkflows()
      ->disableCheckRequiredUserFields()
      ->disableBizProc()
      ->disableAutomation();

    // Step 3: launch operation
    $operationResult = $operation->launch();

    if ( $operationResult->isSuccess() )
    {
      /**
      * Operation success
      */
    }
    else
    {
      /**
      * Operation failed with error
      *
      * @operationResult->getErrors();
      * @operationResult->getErrorMessages();
      */
    }

## Действия в операции
Одна и та же опция для разных типов сущностей может выполнять ряд разных действий в процессе своего выполнения. Чтобы иерархия классов не разрасталась, разработчики приняли решение вынести возможность дополнительные действия в отдельную иерархию классов, чтобы каждый тип сущности мог управлять ими при запуске операций.

Класс \Bitrix\Crm\Service\Operation\Action является абстрактным с единственным методом: abstract public function process(\Bitrix\Crm\Item $item): \Bitrix\Main\Result;

Метод может как прекратить выполнение операции, так и изменить состояние объекта над которым выполняется операция. Для действий выполняемых после сохранения, можно вызвать сохранние элемента ($item->save()) повторно.

Пример добавления дейтвий к полученной операции:

    use \Bitrix\Main,
      \Bitrix\Crm,
      \Bitrix\Crm\Service;

    // ...

    $operation = $factory->getAddOperation($item);

    // ...

    $operation
      ->addAction(
        Service\Operation::ACTION_BEFORE_SAVE,
        new class extends Service\Operation\Action {
          public function process( Crm\Item $item ): Main\Result
          {
            $result = new Main\Result();
            
            if ( mb_strlen($item->getTitle()) < 3 )
            {
              $result->addError( new Main\Error('Title is too short. Need at least 3 symbols') );
            }
            
            return $result;
          }
        }
      )
      ->addAction(
        Service\Operation::ACTION_AFTER_SAVE,
        new class extends Service\Operation\Action {
          public function process( Crm\Item $item ): Main\Result
          {
            return new Main\Result();
          }
        }
      )
    ;

    // ...

    $operationResult = $operation->launch();
