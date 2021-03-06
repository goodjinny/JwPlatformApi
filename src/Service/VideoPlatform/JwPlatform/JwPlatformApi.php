<?php

declare(strict_types=1);

namespace App\Service\VideoPlatform\JwPlatform;

use App\Enum\JwPlayerMetricsField;
use App\Exception\Serializer\DeserializationFailureException;
use App\Exception\Serializer\SymfonySerializerException;
use App\Service\VideoPlatform\JwPlatform\Request\CreateVideoParams;
use App\Service\VideoPlatform\JwPlatform\Request\CreateVideoTrackParams;
use App\Service\VideoPlatform\JwPlatform\Request\CreateWebhookParams;
use App\Service\VideoPlatform\JwPlatform\Request\DeleteVideosParams;
use App\Service\VideoPlatform\JwPlatform\Request\DeleteVideoTrackParams;
use App\Service\VideoPlatform\JwPlatform\Request\FetchThumbnailParams;
use App\Service\VideoPlatform\JwPlatform\Request\ListTracksParams;
use App\Service\VideoPlatform\JwPlatform\Request\ListVideosParams;
use App\Service\VideoPlatform\JwPlatform\Request\ShowVideoParams;
use App\Service\VideoPlatform\JwPlatform\Request\UpdateVideoThumbnailParams;
use App\Service\VideoPlatform\JwPlatform\Request\VideoStatsParams;
use App\Service\VideoPlatform\JwPlatform\Response\AnalyticsQueriesResponse;
use App\Service\VideoPlatform\JwPlatform\Response\ApiResponse;
use App\Service\VideoPlatform\JwPlatform\Response\CreatedWebhookApiResponse;
use App\Service\VideoPlatform\JwPlatform\Response\DeleteVideosResponse;
use App\Service\VideoPlatform\JwPlatform\Response\DeleteVideoTrackResponse;
use App\Service\VideoPlatform\JwPlatform\Response\Partial\AnalyticsQueriesResponseMetadata;
use App\Service\VideoPlatform\JwPlatform\Response\ThumbnailResource;
use App\Service\VideoPlatform\JwPlatform\Response\TracksList;
use App\Service\VideoPlatform\JwPlatform\Response\UploadMetadata;
use App\Service\VideoPlatform\JwPlatform\Response\VideoShowResponse;
use App\Service\VideoPlatform\JwPlatform\Response\VideosList;
use App\Service\VideoPlatform\VideoStats;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JwPlatformApi
{
    public function __construct(
        private SerializerInterface $serializer,
        private NormalizerInterface $normalizer,
        private JwPlatformClient $client,
        private string $siteKey
    ) {
    }

    /**
     * @see https://developer.jwplayer.com/jwplayer/reference#post_videos-create
     */
    public function createVideoMetadata(CreateVideoParams $params): ApiResponse
    {
        $normalizedParams = $this->normalizer->normalize($params);

        $rawResponse = $this->client->requestV1('/videos/create', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                ApiResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function createThumbnailUpdateMetadata(UpdateVideoThumbnailParams $params): ApiResponse
    {
        $normalizedParams = $this->normalizer->normalize(
            $params,
            null,
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );

        $rawResponse = $this->client->requestV1('/videos/thumbnails/update', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                ApiResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function createTrackCreateMetadata(CreateVideoTrackParams $params): ApiResponse
    {
        $normalizedParams = $this->normalizer->normalize(
            $params,
            null,
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );

        $rawResponse = $this->client->requestV1('/videos/tracks/create', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                ApiResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function upload(UploadMetadata $params, string $filePath): void
    {
        $uploadLink = $params->getLink();

        $this->client->uploadFile($filePath, $uploadLink);
    }

    public function fetchAnalytics(
        VideoStatsParams $params
    ): VideoStats {
        $path = "/sites/{$this->siteKey}/analytics/queries/";

        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV2($path, $normalizedParams);

        /** @var AnalyticsQueriesResponse $response */
        $response = $this->serializer->deserialize($rawResponse, AnalyticsQueriesResponse::class, JsonEncoder::FORMAT);

        return $this->buildAnalyticsQueriesVideoStats($response);
    }

    public function createWebhook(CreateWebhookParams $params): CreatedWebhookApiResponse
    {
        $normalizedParams = $this->normalizer->normalize($params);

        $rawResponse = $this->client->requestV2('/webhooks', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                CreatedWebhookApiResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function fetchVideos(ListVideosParams $params): VideosList
    {
        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV1('/videos/list', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                VideosList::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function fetchVideo(ShowVideoParams $params): VideoShowResponse
    {
        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV1('/videos/show', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                VideoShowResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function deleteVideos(DeleteVideosParams $params): DeleteVideosResponse
    {
        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV1('/videos/delete', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                DeleteVideosResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function fetchTracks(ListTracksParams $params): TracksList
    {
        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV1('/videos/tracks/list', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                TracksList::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function deleteTrack(DeleteVideoTrackParams $params): DeleteVideoTrackResponse
    {
        $normalizedParams = $this->normalizer->normalize($params);
        $rawResponse = $this->client->requestV1('/videos/tracks/delete', $normalizedParams);

        try {
            return $this->serializer->deserialize(
                $rawResponse,
                DeleteVideoTrackResponse::class,
                'json'
            );
        } catch (SymfonySerializerException $exception) {
            throw new DeserializationFailureException('Unable to deserialize: "'.$rawResponse.'"', 0, $exception);
        }
    }

    public function fetchThumbnail(FetchThumbnailParams $params): ThumbnailResource
    {
        $fileContent = $this->client->download($name = $params->getThumbFileName());

        $tempFilePath = tempnam(sys_get_temp_dir(), 'thumb');
        $outHandle = fopen($tempFilePath, 'w+');
        fwrite($outHandle, $fileContent);
        fclose($outHandle);

        return new ThumbnailResource(
            $name,
            $tempFilePath,
            null
        );
    }

    private function buildAnalyticsQueriesVideoStats(AnalyticsQueriesResponse $response): VideoStats
    {
        $videoStats = new VideoStats();

        if (!$response->getData()->hasRows()) {
            return $videoStats;
        }

        $jwPlayerRows = $response->getData()->getRows()[0];
        array_shift($jwPlayerRows);

        $metrics = $response->getMetadata()->getColumnHeader(AnalyticsQueriesResponseMetadata::HEADER_METRICS);
        $fields = array_column($metrics, 'field');

        foreach ($fields as $key => $field) {
            switch ($field) {
                case JwPlayerMetricsField::TOTAL_PLAYS->value:
                    $videoStats->setPlays($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::COMPLETE_RATE->value:
                    $videoStats->setCompleteRate($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::UNIQUE_VIEWERS->value:
                    $videoStats->setUniqueViewers($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::PLAY_RATE->value:
                    $videoStats->setPlayRate($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::EMBEDS->value:
                    $videoStats->setEmbeds($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::COMPLETES->value:
                    $videoStats->setCompletes($jwPlayerRows[$key]);
                    break;
                case JwPlayerMetricsField::CONTENT_SCORE->value:
                    $videoStats->setContentScore($jwPlayerRows[$key]);
                    break;
            }
        }

        return $videoStats;
    }
}
