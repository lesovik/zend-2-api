<?php

date_default_timezone_set('GMT');
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return [

    'openRoutes' => [
        'login/post',
    ],

    'service_manager' => [
        'factories' => [
            'Executer' => function ($serviceManager) {
                return new Core\Db\SqlExecuter($serviceManager->get('dbMainAdapter'));
            },
            'ExecuterAdmin' => function ($serviceManager) {
                return new Core\Db\SqlExecuter($serviceManager->get('dbAdminAdapter'));
            },
            'PasswordManager' => function () {
                return new Authentication\Model\Service\PasswordManager();
            },
            'DataValidator' => function () {
                return new Core\Form\DataValidator();
            },
            'HttpClient' => function () {
                return new Zend\Http\Client();
            },
            'HttpRequest' => function () {
                return new Zend\Http\Request();
            },
        ],
    ],
];
