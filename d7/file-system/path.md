# Работаем с путями
Подключаем классы:

    use Bitrix\Main\IO;
    use Bitrix\Main\Application;

    $path = Application::getDocumentRoot() . "/some_dir/some_file.ext";
    $fileExt = IO\Path::getExtension($path); // Возвращает расширение файла
    $fileName = IO\Path::getName($path);     // Возвращает имя файла
    $fileDir = IO\Path::getDirectory($path); // Возвращает директорию файла (полный путь)
