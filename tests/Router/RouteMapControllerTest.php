<?php

declare(strict_types=1);

namespace Test\Router {

    use App\Controller\MainController;
    use App\Controller\NewsController;
    use App\Controller\NewsContactController;
    use App\Controller\Post\DefaultController;
    use Cheremhovo1990\Framework\Router\RouterMapController;
    use PHPUnit\Framework\TestCase;

    class RouteMapControllerTest extends TestCase
    {
        public function testRun()
        {
            $classes = [
                MainController::class,
                NewsController::class,
                NewsContactController::class,
                DefaultController::class,
            ];
            $reflection = new RouterMapController($classes);
            $results = $reflection();

            $this->assert('default', '/', $results[0]);
            $this->assert('main.about', 'main/about', $results[1]);
            $this->assert('contact', '/contact', $results[2]);
            $this->assert('news.show', 'news/show', $results[3]);
            $this->assert('news.edit', 'news/edit', $results[4]);
            $this->assert('news-contact.one-show', 'news-contact/one-show', $results[5]);
            $this->assert('post.default.add', 'post/default/add', $results[6]);
        }

        protected function assert(
            $first,
            $second,
            array $route
        )
        {
            $this->assertEquals($first, $route[0]);
            $this->assertEquals($second, $route[1]);
        }
    }
}

namespace App\Controller {

    use Cheremhovo1990\Framework\Router\Attribute\Route;
    use Cheremhovo1990\Framework\Router\Attribute\RouteGroup;

    #[Route('default', '/')]
    class MainController
    {
        public function __invoke()
        {
            return '__invoke_function';
        }
        public function aboutAction()
        {
            return 'about_function';
        }

        #[Route('contact', '/contact')]
        public function contact()
        {
            return 'contact_function';
        }
    }

    #[RouteGroup('news', 'news')]
    class NewsController
    {
        public function showAction()
        {
            return 'show_function';
        }

        #[Route('edit', '/edit')]
        public function edit()
        {
            return 'edit_function';
        }
    }

    class NewsContactController
    {
        public function oneShowAction()
        {
            return 'news_show_one_show';
        }
    }
}

namespace App\Controller\Post {
    class DefaultController
    {
        public function addAction()
        {
            return 'post_default_add';
        }
    }
}