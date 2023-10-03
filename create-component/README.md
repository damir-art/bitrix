# Создание компонентов
- Создание компонента на D7: https://hmarketing.ru/blog/bitrix/sozdanie-prostogo-komponenta-v-d7/
- Создание комопненты фформы: https://www.bbitrix.ru/pages/components/bitriks-komponent-obratnaya-svyaz-ajax/
- Создание компонента формы: https://it-svalka.ru/blog/bitrix/sozdanie-kontaktnoy-formy-po-kanonam-bm/

Переменные Битрикс, доступные в компоненте: https://ut11-web.ru/faq-1c-bitrix/variables-available-in-the-component-2-0-bitrix/

Курс по созданию компонентов Битрикс.  
https://www.youtube.com/@denisgorelov3236/videos

# №1 Что такое компонент
- что такое компонент
- принцип работы CMS
- виды компонентов

Сайт состоит из двух частей: публичная, административная. В Битрикс вся информация огранизована в виде инфоблоков. Компоненты нужны для вывода информации в публичной части.

Битрикс состоит из 5 частей:
- база данных
- ядро Битрикс (API)
- модули
- комопненты (формирует данные из модулей и передаёт их в шаблон)
- шаблон комопнента (вывод информации)

Компоненты могут выводить различную информаци: одну или несколько новостей, меню, поиск, хлебные крошки, различные формы (почтовые, авторизации) и т.д. Список доступных компонентов хранится в форме редактирования страницы.

Структура компонента разделена на логику (сам компонент) и представление (шаблон вывода). Для одной логики можно создать несколько представлений.

Компоненты делятся на два вида, простые (одностраничные) и комплексные (многостраничные, объединяют набор простых компонентов, есть поддержка ЧПУ). Комплексный компонент размещается на одной физической странице и автоматом строит динамическую структуру на базе простых компонентов.

# №2 Размещение
- Размещение компонентов в системе
- Наименование компонентов
- Подключение компонентов

Размещение компонентов зависят от того системные они или пользовательские:
- системные:
  - `bitrix/components/bitrix/` - там ниче менять нельзя
  - `local/components/bitrix/` - тут менять можно
- пользовательские:
  - `bitrix/components/` - в этой же директории
  - `bitrix/components/damir/` - в своём пространстве
  - `local/components/damir/` - в папке local и своём пространстве
  - `local/components/damir/news.list/` - скопировали в папку local, в своё пространство, системный компонент

Папка являющаяся пространством имём важна, именно её мы видим в компоненте когда открываем его настройки в публичной части сайта при `Режим правки включен`. Если открыть настрйоки меню то путь комопнента будет начинаться с пространства `bitrix:`.

Чтобы получить скопированный комопнент `local/components/damir/news.list/` в списке компонентов справа в визуальном редакторе, обновите кеш (там же `Компоненты > Обновить`). При перетаскивании его в редактор пространство уже будет `damir:`.

Имя комопнента может состоять из одного или нескольких слов разделённых точкой, например `news.list`:
- news - идентификатор 1
- list - идентификатор 2
`list` - простой компонент относящийся к комплексному news.

Подключение компонентов на странице.  
Открываем на редактирование нужную страницу и перетаскиваем компонент. Можно также код прописать вручную.  
Каждый компонент имеет свой набор параметров.  

    $APPLICATION->IncludeComponent(
      "damir:news.list", // простарнство имён и имя компонента
      "",                // шаблон компонента (.default)
      Array()            // настройки компонента, массив входящих параметров
    );