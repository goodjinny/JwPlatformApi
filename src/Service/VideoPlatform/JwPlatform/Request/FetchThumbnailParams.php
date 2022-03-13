<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Request;

final class FetchThumbnailParams
{
    public function getVideoKey(): string
    {
        return '';
    }

    public function getThumbWidth(): int
    {
        return 128;
    }
}