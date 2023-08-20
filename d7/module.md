# Подключение модулей
Подключение модулей, например инфоблока.

    // Old school
    // CModule::IncludeModule("iblock");
    CModule::IncludeModuleEx("intervolga.tips");

    // D7
    use Bitrix\Main\Loader;
    Loader::includeModule("iblock");
    // Loader::includeSharewareModule("intervolga.tips");

## Чтение и запись настроек модулей

    // Old school
    COption::SetOptionString("main", "max_file_size", "1024");
    $size = COption::GetOptionInt("main", "max_file_size");
    COption::RemoveOption("main", "max_file_size", "s2");

    // D7
    use Bitrix\Main\Config\Option;

    Option::set("main", "max_file_size", "1024");
    $size = Option::get("main", "max_file_size");
    Option::delete("main", array(
      "name" => "max_file_size",
      "site_id" => "s2"
      )
    );