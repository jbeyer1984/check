<?php

namespace DI;

use Check\App\User\Session\UserAuthentificationSession;
use Check\Globals\Session;

return [
    Session::class => create(Session::class),
    UserAuthentificationSession::class => create()
        ->constructor(get(Session::class))
];
