<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности: [ex2-88]");
?>

    <p>
        Страница:
        <a href="http://bx-exam2.loc/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fproducts%2Findex.php">
            /products/index.php
        </a>
        <br>
        Доля нагрузки: 24.17%
    </p>

    <p>
        Кэш компонента simplecomp:ex2-70<br>
        - Кеш «по умолчанию»: 122 КБ<br>
        - Кеш при помещении в него только данных, необходимых в некешируемой части: 71 КБ
        - Разница: 51 КБ
    </p>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>