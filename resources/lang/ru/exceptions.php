<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 11:08 AM
 */


return [

    'invitation' => [
        'alreadyExists' => 'Сотрудник с e-mail адресом :email уже присоединился к Вашей компании!',
        'limitReached' => 'Невозможно отправить приглашение на :email, так как Ваша компания привысила максимальный лимит приглашений - :limit приглашения на один e-mail'
    ],


    'restore-password' => [
        'notFound' => 'Сотрудник с e-mail адресом :email не найден на Jincor'
    ],

    'change-password' => [
        'mismatch' => 'Неверный старый пароль'
    ],
    'login' => [
        'multiple_companies' => 'Указанные логин и пароль совпадают с данными из нескольких компаний. Пожалуйста, укажите companyId для входа'
    ],
    'employee' => [
        'not_found' => 'Сотрудник :email не найден',
        'company_required' => 'Сотрудник обязательно должен быть привязан к компании',
        'password_mismatch' => 'Некорректный логин или пароль',
        'access_denied' => 'Отказ в доступе. У Вас недостаточно прав для совершения этого действия',
        'inactive' => 'Аккаунт не был активирован',
    ],
    'company' => [
        'not_found' => 'Компания не найдена',
    ],
    'country' => [
        'not_found' => 'Страна не найдена',
    ],
    'city' => [
        'not_found' => 'Город не найден',
    ],
    'economical_activity_type' => [
        'not_found' => 'Вид Экономической Деятельности не найден',
    ],
    'company_type' => [
        'not_found' => 'Тип компании не найден',
    ]

];