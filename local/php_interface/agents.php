<?php

/**
 * [ex2-54] Подсчет количества зарегистрированных пользователей
 *
 * @return string
 */
function CheckUserCount()
{
    // Текущая дата и время, вид: 06.12.2019 10:10:10
    $sDateNow = ConvertTimeStamp(time(), "FULL");

    // Дату подсчета сохранять и получать с помощью параметров модулей
    $sLastDate = COption::GetOptionString("main", "last_date_agent_checkUserCount");

    // Формируем фильтр по дате
    if (!empty($sLastDate)) {
        $arFilter = [">=DATE_REGISTER" => $sLastDate];
    } else {
        $arFilter = [];
    }

    // Получаем отфильтрованных пользователей, отсортированных по дате регистрации
    $rsUsers = CUser::GetList($by = "DATE_REGISTER", $order = "ASC", $arFilter);
    $arUsers = [];
    while ($arUser = $rsUsers->Fetch()) {
        $arUsers[] = $arUser;
    }

    // Количество зарегистрированных пользователей за период времени
    $iCountUsers = count($arUsers);

    // Если подсчет производился впервые, получаем самую раннюю дату регистрации
    if (empty($sLastDate)) {
        $sLastDate = $arUsers[0]["DATE_REGISTER"];
    }

    // За какое количество дней произведен подсчет
    $iDifference = intval(strtotime($sDateNow) - strtotime($sLastDate));
    $iDays = round($iDifference / (3600 * 24));

    // Отправляем письмо всем пользователям из группы "Администраторы"
    $rsAdmins = CUser::GetList($by = "ID", $order = "ASC", ["GROUPS_ID" => 1]);
    while ($admin = $rsAdmins->Fetch()) {
        CEvent::Send(
            "COUNT_REGISTERED_USERS",
            SITE_ID,
            [
                "EMAIL_TO"    => $admin["EMAIL"],
                // Количество зарегистрированных пользователей за период времени
                "COUNT_USERS" => $iCountUsers,
                // За какое количество дней произведен подсчет
                "COUNT_DAYS"  => $iDays,
            ],
            "N",
            "30"
        );
    }

    // Дату подсчета сохранять и получать с помощью параметров модулей
    COption::SetOptionString("main", "last_date_agent_checkUserCount", $sDateNow);

    return "CheckUserCount();";
}