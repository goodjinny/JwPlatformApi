<?php

declare(strict_types=1);

namespace App\DTO\Response;

final class AnalyticsQueriesResponseMetadata
{
    public const HEADER_METRICS = 'metrics';

    private array $columnHeaders;

    public function getColumnHeaders(): array
    {
        return $this->columnHeaders;
    }

    public function setColumnHeaders(array $columnHeaders): self
    {
        $this->columnHeaders = $columnHeaders;

        return $this;
    }

    public function hasColumnHeader(string $name): bool
    {
        return isset($this->columnHeaders[$name]);
    }

    public function getColumnHeader(string $name): mixed
    {
        return $this->columnHeaders['metrics'];
    }
}