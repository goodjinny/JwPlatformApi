<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Response;

final class ThumbnailResource
{
    public const MIME_TYPE_JPEG = 'image/jpeg';
    public const MIME_TYPE_PNG = 'image/png';

    public const MIME_TYPES = [
        self::MIME_TYPE_JPEG,
        self::MIME_TYPE_PNG,
    ];

    private string $fileName;

    private string $filePath;

    private string $mimeType = self::MIME_TYPE_JPEG;

    public function __construct(string $fileName, string $filePath, ?string $mimeType = null)
    {
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        if (in_array($mimeType, self::MIME_TYPES)) {
            $this->mimeType = $mimeType;
        }
    }
}
