<?php

declare(strict_types=1);

namespace App\Controller;

use Cheremhovo1990\Framework\Helper\UrlHelper;
use Cheremhovo1990\Framework\Http\BaseControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Cheremhovo1990\Framework\Router\Attribute\Route;

#[Route('default', '/')]
class DefaultController extends BaseControllerAbstract
{
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->render('main',
            ['title' => 'Home']
        );
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