<?php

namespace SimpleDAV\Controllers;

use PicoFarad\Response;
use PicoFarad\Router;
use PicoFarad\Session;
use SimpleDAV\Model\User;

Router\before(function ($action) {

    Session\open(BASE_DIRECTORY, '', 0);

    $loggedIn = User::loggedIn();
    if (!isset($loggedIn) && $action !== 'login') {
        User::logout();
        Response\redirect('?action=login');
    }

    $adminActions = [
        'users',
        'add-user',
        'confirm-delete-user',
        'delete-user',
        'confirm-delete-calendar',
        'delete-calendar'
    ];
    if (!User::isAdmin() && \in_array($action, $adminActions)) {
        Session\flash_error('Permission denied.');
        Response\redirect('?action=overview');
    }

    Response\csp([
        'media-src' => '*',
        'img-src' => '*',
        'style-src' => [
            "'self'",
            'https://fonts.googleapis.com',
            'https://maxcdn.bootstrapcdn.com',
        ],
        'font-src' => [
            "'self'",
            'https://fonts.gstatic.com',
            'https://maxcdn.bootstrapcdn.com'
        ],
        'script-src' => [
            "'self'",
            'https://code.jquery.com',
            'https://maxcdn.bootstrapcdn.com'
        ]
    ]);
    Response\xframe();
    Response\xss();
    Response\nosniff();
});
