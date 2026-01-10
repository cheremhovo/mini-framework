<?php

declare(strict_types=1);

namespace App\Controller;

use Cheremhovo1990\Framework\Router\Attribute\Route;
use Cheremhovo1990\Framework\Router\Attribute\RouteGroup;

#[RouteGroup('main', '/main')]
class MainController
{
    #[Route('.about', '/about')]
    public function about()
    {
        return 'main.about';
    }
}