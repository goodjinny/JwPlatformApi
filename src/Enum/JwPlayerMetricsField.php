<?php

declare(strict_types=1);

namespace App\Enum;

enum JwPlayerMetricsField: string
{
    case TOTAL_PLAYS = 'plays';
    case COMPLETE_RATE = 'complete_rate';
    case UNIQUE_VIEWERS = 'unique_viewers';
    case PLAY_RATE = 'play_rate';
    case EMBEDS = 'embeds';
    case COMPLETES = 'completes';
    case CONTENT_SCORE = 'content_score';
}