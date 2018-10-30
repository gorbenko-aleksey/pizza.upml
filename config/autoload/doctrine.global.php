<?php

return [
    'doctrine' => [
        'connection' => [
            // default connection name
            'orm_default' => [
                'driverClass' => Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                    'user' => 'root',
                    'password' => '0nKNPgt1vu',
                    'dbname' => 'upml_db',
                    'charset' => 'UTF8',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND =>
                            'SET NAMES "UTF8",'
                            . ' CHARACTER SET "UTF8",'
                            . ' time_zone = "+00:00"',
                    ],
                ],
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    Gedmo\Timestampable\TimestampableListener::class,
                    Gedmo\Uploadable\UploadableListener::class,
                    Gedmo\Sluggable\SluggableListener::class,
                    Gedmo\Sortable\SortableListener::class,
                    Gedmo\Tree\TreeListener::class,
                ],
            ],
        ],
    ],
];
