<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform;

final class VideoStats
{
    private iterable $stats = [];

    public function getStatus(): iterable
    {
        return $this->stats;
    }

    public function setPlays(string $key): void
    {
        $this->stats = [];
    }

    public function setCompleteRate(string $key): void
    {
        $this->stats = [];
    }

    public function setUniqueViewers(string $key): void
    {
        $this->stats = [];
    }

    public function setPlayRate(string $key): void
    {
        $this->stats = [];
    }

    public function setEmbeds(string $key): void
    {
        $this->stats = [];
    }

    public function setCompletes(string $key): void
    {
        $this->stats = [];
    }

    public function setContentScore(string $key): void
    {
        $this->stats = [];
    }
}