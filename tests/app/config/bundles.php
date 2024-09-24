<?php

declare(strict_types=1);

use Shrikeh\HelloWorld\Bundle\HelloWorld;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Shrikeh\SymfonyApp\Bundle\App;

return [
    FrameworkBundle::class => ['all' => true],
    App::class => ['all' => true],
    HelloWorld::class  => ['all' => true],
];
