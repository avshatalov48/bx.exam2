<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности: [ex2-10]");
?>
    <p>
        Страница:
        <a href="http://bx-exam2.loc/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fproducts%2Findex.php">
            /products/index.php
        </a>
        <br>
        Доля нагрузки: 31.25%
    </p>

    <p>
        Самый нагружаемый компонент: bitrix:catalog<br>
        Время его работы: 0.064 с
    </p>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>