<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Response;

final class UploadMetadata
{
    private string $link;

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
