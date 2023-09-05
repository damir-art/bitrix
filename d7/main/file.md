# Файловая структура
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
