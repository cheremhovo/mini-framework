<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Container;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundServiceException extends \InvalidArgumentException implements NotFoundExceptionInterface
{

}