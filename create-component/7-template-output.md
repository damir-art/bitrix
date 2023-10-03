# Вывод информации
Вывод информации из инфоблока в шаблон.  
Вывод динамической информации в шаблоне комопнента.

- `$arResult` - работа с массивом,
- Перебор элементов в цикле,
- Проверка на наличие свойств,
- Редактирование удаление элементов.

## $arResult
Массив `$arResult` формируется в файле `component.php`.

Выведем его в файле `template.php`:

    echo '<pre>';
    print_r( $arResult );
    echo '</pre>';

В верстке оставляем только один сверстанный элемент.
- Путь к изображению: `$arResult['ITEMS']['0']['PREVIEW_PICTURE']['SRC']`
- Имя сотрудника: `$arResult['ITEMS']['0']['NAME']`

Все свойства хранятся в массиве PROPERTIES:
- Должность сотрудника: `$arResult['ITEMS']['0']['DISPLAY_PROPERTIES']['STATE']['VALUE']`
- Социальная сеть: `$arResult['ITEMS']['0']['DISPLAY_PROPERTIES']['FACEBOOK']['VALUE']`

В массиве DISPLAY_PROPERTIES хранятся свойства которые вы выделили в настройках компонента: `Источник данных > Свойства > Выделяем нужные`.

## foreach

Проходимся циклом по массиву элементов:

    <section class="dws-wrapper">
      <div class="flex-container">
        <?php foreach( $arResult['ITEMS'] as $arItem ): ?>
          <div class="imageBox">
            <img src="<?php echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" />
            <h3><?php echo $arItem['NAME']; ?></h3>
            <p><?php echo $arItem['DISPLAY_PROPERTIES']['STATE']['VALUE']; ?></p>
            <ul>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['FACEBOOK']['VALUE']; ?>">
                <i class="fa fa-facebook"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['TWITTER']['VALUE']; ?>">
                <i class="fa fa-twitter-square"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['GOOGLE_PLUS']['VALUE']; ?>">
                <i class="fa fa-google-plus"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['LINKEDIN']['VALUE']; ?>">
                <i class="fa fa-linkedin"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['INSTAGRAM']['VALUE']; ?>">
                <i class="fa fa-instagram"></i></a></li>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

## if
Проверяем выделена ли должность в `Источник данных > Свойства > Выделяем нужные`, если не сделать проверку и убрать выделение со свойства Должность, то должно не будет показать на стили должности выведутся. В условия желательно завернуть всю динамическую информацию управляемую компонентом.

    <?php if( $arItem['DISPLAY_PROPERTIES']['STATE']['VALUE'] ): ?>
      <p><?php echo $arItem['DISPLAY_PROPERTIES']['STATE']['VALUE']; ?></p>
    <?php endif; ?>

## Редактирование удаление элементов
https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=43&LESSON_ID=3853  
Через визуальную часть редактируем и удаляем элементы инфоблока, не переходя в административный раздел.
- `AddEditAction()` - редактировать элемент,
- `AddDeleteAction()` - удалить элемент, 
- `GetEditAreaID()` - отображение панели,
- `GetArrayByID()`,
- `SetEditArea()`.

Пример кода:

    <section class="dws-wrapper">
      <div class="flex-container">
        <?php foreach( $arResult['ITEMS'] as $arItem ): ?>
          <?php
            // Параметры: ID элемента, ссылка для редактирования, Название кнопки
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
            // Кнопка удаления элемента без перехода в админку
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => 'Удалить элемент?'));
          ?>
          <!-- Кнопка изменения элемента без перехода в админку GetEditAreaID -->
          <div class="imageBox" id="<?php echo $this->GetEditAreaID($arItem['ID']); ?>">
            <img src="<?php echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" />
            <h3><?php echo $arItem['NAME']; ?></h3>
            <?php if( $arItem['DISPLAY_PROPERTIES']['STATE']['VALUE'] ): ?>
              <p><?php echo $arItem['DISPLAY_PROPERTIES']['STATE']['VALUE']; ?></p>
            <?php endif; ?>
            <ul>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['FACEBOOK']['VALUE']; ?>">
                <i class="fa fa-facebook"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['TWITTER']['VALUE']; ?>">
                <i class="fa fa-twitter-square"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['GOOGLE_PLUS']['VALUE']; ?>">
                <i class="fa fa-google-plus"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['LINKEDIN']['VALUE']; ?>">
                <i class="fa fa-linkedin"></i></a></li>
              <li>
                <a href="<?php echo $arItem['DISPLAY_PROPERTIES']['INSTAGRAM']['VALUE']; ?>">
                <i class="fa fa-instagram"></i></a></li>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
