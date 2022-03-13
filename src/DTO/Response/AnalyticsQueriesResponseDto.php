<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\Exception\Response\UnexpectedResponseException;

final class AnalyticsQueriesResponseDto
{
    private AnalyticsQueriesResponseMetadata $metadata;

    private AnalyticsQueriesResponseData $data;

    public function __construct()
    {
        // do some stuff for creating response object
        $this->validate();
    }

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

    private function validate(): void
    {
        if (!$this->getMetadata()->hasColumnHeader(AnalyticsQueriesResponseMetadata::HEADER_METRICS)) {
            throw new UnexpectedResponseException('Unexpected JWPlayer analytics metrics response: ' . $this->getRawContent());
        }

        // this is definitely incorrect validation. The situation where response consists incorrect mediaId should not be happen
        if ($this->getData()->hasRows()) {
            $jwPlayerRows = $this->getData()->getRows()[0];
            if ($jwPlayerRows[0] !== $params->getMediaId()) {
                throw new UnexpectedResponseException('Unexpected JWPlayer analytics rows response: ' . $this->getRawContent());
            }
        }
    }

    private function getRawContent(): string
    {
        return 'response row content';
    }
}