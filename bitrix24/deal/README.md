# Сделки
Работаем со сделками.

## Получаем поля сделки
Работаем со сделками через фабрику:

    $dealFactory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
    $fieldsCollection = $dealFactory->getFieldsCollection();

    foreach ($fieldsCollection as $field) {
      echo $field->getName();
      echo ': ';
      echo $field->getTitle();
      echo '<br />';
    }