<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности: [ex2-11]");
?>
    <p>
        Страница:
        <a href="http://bx-exam2.loc/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fproducts%2Findex.php">
            /products/index.php
        </a>
        <br>
        Среднее время выполнения: 0.1194 сек.
    </p>

    <p>
        Больше всего запросов в компоненте: bitrix:catalog.section<br>
        Количество запросов: 27
    </p>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>