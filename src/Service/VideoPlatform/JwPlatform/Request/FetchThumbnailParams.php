<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Request;

final class FetchThumbnailParams
{
    public const EXT_JPEG = 'jpg';

    private string $videoKey;

    private int $thumbWidth;

    private string $ext = self::EXT_JPEG;

    public function getVideoKey(): string
    {
        return $this->videoKey;
    }

    public function setVideoKey(string $videoKey): self
    {
        $this->videoKey = $videoKey;

        return $this;
    }

    public function getThumbWidth(): int
    {
        return $this->thumbWidth;
    }

    public function setThumbWidth(int $thumbWidth): self
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    public function getExt(): string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }

    public function getThumbFileName(): string
    {
        return "$this->videoKey-$this->thumbWidth.$this->ext";
    }
}
