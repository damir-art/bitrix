# Работаем с директориями
Подключаем классы:

    use Bitrix\Main\IO;
    use Bitrix\Main\Application;

Получаем доступ к директории:

    $dir = new IO\Directory(Application::getDocumentRoot() . '/test/');

Если директории не существует, её можно создать:

    $dir->create(); // создаёт директорию с указанным в конструкторе путём

Информация о директории:

    $isExist = $dir->isExists(); // true, если директория существует

    $createdAt = $dir->getCreationTime(); // дата создания, timestamp
    $accessAt = $dir->getLastAccessTime(); // дата последнего доступа, timestamp
    $modifiedAt = $dir->getModificationTime(); // дата модификации, timestamp

    $perms = $dir->getPermissions(); // права на директорию в виде десятичного числа
    $perms = substr(sprintf('%o', $dir->getPermissions()), -3); // права на директорию в виде восьмеричного числа

Действия над директориями:

    $childDir = $dir->createSubdirectory("child"); // создает и возвращает вложенную директорию с указанным именем
    $dir->rename(Application::getDocumentRoot() . "/another_path/"); // переместить/переименовать директорию
    $dir->delete(); // удалить директорию

Получить массив файлов в директории:

    $files = $dir->getChildren(); // массив объектов IO\File

У некоторых методов есть статические варианты:

    $path = Application::getDocumentRoot() . "/another_dir/";
    IO\Directory::createDirectory($path);   // cоздать директорию
    IO\Directory::deleteDirectory($path);   // удалить директорию
    IO\Directory::isDirectoryExists($path); // проверить существование
