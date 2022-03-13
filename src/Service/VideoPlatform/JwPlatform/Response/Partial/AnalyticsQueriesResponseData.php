<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform\Response\Partial;

final class AnalyticsQueriesResponseData
{
    private array $rows;

    public function getRows(): array
    {
        return $this->rows;
    }

    public function setRows(array $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function hasRows(): bool
    {
        return count($this->rows) > 0;
    }
}
