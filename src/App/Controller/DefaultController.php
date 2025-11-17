<?php

declare(strict_types=1);

namespace App\Controller;

use Cheremhovo1990\Framework\Helpers\UrlHelper;
use Psr\Http\Message\ServerRequestInterface;
use Cheremhovo1990\Framework\Router\Attribute\Route;

#[Route('default', '/')]
class DefaultController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $url = UrlHelper::to('show', ['id' => 10, 'name' => 'test', 'title' => 'Привет мир!!!']);
        return '<a href="'.$url.'">' . $url . '</a>';
    }

    #[Route('show', '/show/{id:\d+}', options: ['requirements' => ['name' => '\w+']])]
    public function show(ServerRequestInterface $request)
    {
        return 'show!!!';
    }

    public function editAction()
    {
        return 'edit!!!';
    }
}