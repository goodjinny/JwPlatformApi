<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Request;

final class VideoStatsParams
{
    private int $mediaId;

    public function getMediaId(): int
    {
        return $this->mediaId;
    }

    public function setMediaId(int $mediaId): self
    {
        $this->mediaId = $mediaId;

        return $this;
    }
}
