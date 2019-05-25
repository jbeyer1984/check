<?php

namespace DI;

use Check\App\Product\Factory\ProductFactory;
use Check\App\Product\Repository\ProductRepository;
use Check\Persistence\Repository\BaseRepository;
use Check\App\User\Factory\UserFactory;
use Check\App\User\Factory\UserSessionFactory;
use Check\App\User\Repository\UserRepository;
use Check\App\User\Repository\UserSessionRepository;
use Check\Globals\Database;
use Check\Persistence\Repository\Decorator\RepositoryTemporaryPersistenceDecorator;
use Check\Persistence\Temporary\TemporaryPersistence;

return [
    
    BaseRepository::class        => create()
        ->constructor(
            get(Database::class),
            get(TemporaryPersistence::class)
        ),
    RepositoryTemporaryPersistenceDecorator::class => create()
        ->constructor(
            get(BaseRepository::class),
            get(TemporaryPersistence::class)
        ),
    
    'persistence' => get(RepositoryTemporaryPersistenceDecorator::class),
    
//    BaseRepositoryInterface::class => create()
//        ->constructor(BaseRepository::class, $)
    UserRepository::class        => create()
        ->constructor(
            get('persistence'),
            get(UserFactory::class)
        ),
    UserSessionRepository::class => create()
        ->constructor(
            get('persistence'),
            get(UserSessionFactory::class)
        ),
    ProductRepository::class => create()
        ->constructor(
            get('persistence'),
            get(ProductFactory::class)
        )
];
