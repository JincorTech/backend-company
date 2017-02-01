<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Неверный логин или пароль',
    'throttle' => 'Слишком много попыток входа. Попробуйте снова через :seconds секунд.',
    'exceptions' => [
            'matching-companies-unauthorized' => 'Вы дожны предоставить хотя бы один из следующий наборов параметров для запроса списка компаний: email & password, invitationId либо быть аутентифицированным сотрудникам'
    ],

];
