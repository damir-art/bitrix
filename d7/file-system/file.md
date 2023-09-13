# Работаем с файлами
Подключаем классы:

    use Bitrix\Main\IO;
    use Bitrix\Main\Application;

Подключение к файлу:

    $file = new IO\File(Application::getDocumentRoot() . "/file.txt");

Информация о файле:

    $isExist = $file->isExists(); // true, если файл существует

    $dir = $file->getDirectory(); // директория файла в виде объекта IO\Directory
    $dir = $file->getDirectoryName(); // директория файла в виде текста

    $fileName = $file->getName(); // имя файла
    $fileExt = $file->getExtension(); // расширение файла
    $fileSize = $file->getSize(); // размер файла в байтах
    $contentType = $file->getContentType(); // content-type

    $createdAt = $file->getCreationTime(); // дата создания, timestamp
    $accessAt = $file->getLastAccessTime(); // дата последнего доступа, timestamp
    $modifiedAt = $file->getModificationTime(); // дата модификации, timestamp

    $perms = $file->getPermissions(); // права на файл в виде десятичного числа
    $perms = substr(sprintf('%o', $file->getPermissions()), -3); // права на файл в виде восьмеричного числа

Действия над файлами:

    $content = $file->getContents(); // получить содержимое файла
    $file->putContents("data"); // записать содержимое в файл с заменой
    $file->putContents("\ndata", IO\File::APPEND); // дописать содержимое в конец файла
    $file->readFile(); // вывести содержимое файла

    $file->rename(Application::getDocumentRoot() . "/new_file.txt"); // переместить/переименовать файл
    $file->delete(); // удалить файл

У некоторых методов есть статические варианты:

    $path = Application::getDocumentRoot() . "/another_file.txt";
    IO\File::isFileExists($path); // проверить файл на существование

    IO\File::getFileContents($path); // получить содержание файла
    IO\File::putFileContents($path, "data"); // записать содержимое в файл с заменой
    IO\File::putFileContents($path, "data", self::APPEND); // дописать содержимое в конец файла

    IO\File::deleteFile($path); // Удалить файл

## Старая запись
Работаем с файлами и файловой структорой в D7.

- Bitrix\Main\IO\Directory
- Bitrix\Main\IO\File
- Bitrix\Main\IO\Path

Примеры:

    // Old school
    CheckDirPath($_SERVER["DOCUMENT_ROOT"] . "/foo/bar/baz/");
    RewriteFile(
      $_SERVER["DOCUMENT_ROOT"] . "/foo/bar/baz/1.txt",
      "hello from old school!"
    );
    DeleteDirFilesEx("/foo/bar/baz/");

    // D7
    use Bitrix\Main\Application;
    use Bitrix\Main\IO\Directory;
    use Bitrix\Main\IO\File;

    Directory::createDirectory(
      Application::getDocumentRoot() . "/foo/bar/baz/"
    );
    File::putFileContents(
    Application::getDocumentRoot() . "/foo/bar/baz/1.txt",
      "hello from D7"
    );
    Directory::deleteDirectory(
      Application::getDocumentRoot() . "/foo/bar/baz/"
    );
