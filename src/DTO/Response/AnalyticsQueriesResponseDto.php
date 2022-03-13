<?php

declare(strict_types=1);

namespace App\DTO\Response;

final class AnalyticsQueriesResponseDto
{
    private AnalyticsQueriesResponseMetadata $metadata;

    private AnalyticsQueriesResponseData $data;

    public function getMetadata(): AnalyticsQueriesResponseMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(AnalyticsQueriesResponseMetadata $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getData(): AnalyticsQueriesResponseData
    {
        return $this->data;
    }

    public function setData(AnalyticsQueriesResponseData $data): self
    {
        $this->data = $data;

        return $this;
    }
}