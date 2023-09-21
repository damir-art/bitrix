# Пользователи
https://prog-time.ru/shpargalka-po-bitrix-rabota-s-polzovatelskimi-polyami/

Выборка всех пользователей:

    $userListObj = \Bitrix\Main\UserTable::getList();
    $userArrs = $userListObj->fetchAll();

    echo '<pre>';
    print_r($userArrs);
    echo '</pre>';

Показать ID и LOGIN пользователей:

    $userListObj = \Bitrix\Main\UserTable::getList([
      'select' => [ 'ID', 'LOGIN' ]
    ]);
    $userArrs = $userListObj->fetchAll();

    echo '<pre>';
    print_r($userArrs);
    echo '</pre>';

Получаем ID пользователя по его LOGIN

    use Bitrix\Main\UserTable;

    $user = UserTable::getList([
      'select' => ['ID'],
      'filter' => ['LOGIN' => 'akimova']
    ])->fetch();

    $user['ID'];
