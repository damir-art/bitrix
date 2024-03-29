# ORM
https://www.intervolga.ru/blog/projects/orm-om-nom-nom-ili-tayny-orm-v-1c-bitrix/  
https://hmarketing.ru/blog/bitrix/orm-v-novom-yadre/  
https://www.youtube.com/watch?v=1_xYUQzQHj8  
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&CHAPTER_ID=05748  
Операции с сущностями: https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2244  
Курс: https://academy.1c-bitrix.ru/education/?COURSE_ID=85&LESSON_ID=7255

https://o2k.ru/blog/dev/avto-gen-orm-klassov-dlya-tablic - создаём ORM для пользовательской таблицы  
https://sreggh.ru/article/tablicy-bd-bitrix  
https://sreggh.ru/article/rabota-s-polzovatelyami-v-bitrix  

События: https://estrin.pw/bitrix-d7-snippets/s/orm-highloadblock-events/

https://sreggh.ru/article/metody-orm-bitrix

ORM (англ. Object-relational mapping, рус. Объектно-реляционное отображение) - технология программирования, которая связывает базы данных с концепциями объектно-ориентированных языков программирования, создавая «виртуальную объектную базу данных».

В старом ядре на каждую сущность программируется свой `GetList`, `Update`, `Add`, `Delete`.

Недостатки такой идеологии:
- разный набор параметров;
- разный синтаксис полей фильтров;
- события могут быть или не быть;
- иногда разный код под разные БД (Add).

Операции выборки и сохранения в БД - однотипные, с одинаковыми параметрами и фильтрами. Стандартные события добавления/изменения/удаления доступны автоматически.

Для реализации этих целей введены понятия:
- Cущности (Bitrix\Main\Entity\Base);
- Поля сущностей (Bitrix\Main\Entity\Field и его наследники);
- Датаменеджер (Bitrix\Main\Entity\DataManager).

Сущность описывает таблицу в БД, в том числе содержит поля сущностей. Датаменеджер производит операции выборки и изменения сущности. На практике же работа в основном ведется на уровне датаменеджера.
