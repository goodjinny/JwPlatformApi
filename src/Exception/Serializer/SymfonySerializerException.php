<?php

declare(strict_types=1);

namespace App\Exception\Serializer;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

class SymfonySerializerException extends \Exception implements ExceptionInterface
{
}
