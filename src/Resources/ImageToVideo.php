<?php

declare(strict_types=1);

namespace RunApi\Vidu\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Vidu\Models\CompletedVideoTaskResponse;
use RunApi\Vidu\Models\VideoTaskResponse;

/**
 * Animates a still first-frame image into video, guided by an optional text prompt.
 */
readonly class ImageToVideo extends TypedConfiguredResource
{
    /**
     * Submits an image-to-video task and returns immediately with a task id.
     *
     * @param array{model: string, first_frame_image_url: string, prompt?: string, audio?: bool, duration_seconds?: int, output_resolution?: string, callback_url?: string} $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of an image-to-video task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits an image-to-video task and polls until it completes.
     *
     * @param array{model: string, first_frame_image_url: string, prompt?: string, audio?: bool, duration_seconds?: int, output_resolution?: string, callback_url?: string} $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/vidu/image_to_video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            'image-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
