<?php

declare(strict_types=1);

namespace RunApi\Vidu\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Vidu\ViduClient;

final class ViduClientTest extends TestCase
{
    public function testBothResourcesPreserveFalseAndArrays(): void
    {
        foreach (['imageToVideo' => 'image_to_video','referenceToVideo' => 'reference_to_video'] as $resource => $path) {
            $t = new QueueHttpClient([new Response(200, [], '{"id":"task_1"}'),new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://cdn.runapi.ai/public/samples/video.mp4"}]}'),new Response(200, [], '{"id":"task_1"}'),new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://cdn.runapi.ai/public/samples/video.mp4"}]}')]);
            $c = new ViduClient(new ClientOptions(apiKey:'test', httpClient:$t, maxRetries:0));
            $params = ['model' => $resource === 'imageToVideo' ? 'vidu-q4-image-to-video' : 'vidu-q4-reference-to-video','audio' => false,'duration_seconds' => 5,'prompt' => 'move'];
            if ($resource === 'imageToVideo') {
                $params['first_frame_image_url'] = 'https://cdn.runapi.ai/public/samples/image.jpg';
            } else {
                $params['reference_image_urls'] = ['https://cdn.runapi.ai/public/samples/image.jpg','https://cdn.runapi.ai/public/samples/image.jpg'];
                $params['reference_audio_urls'] = ['https://cdn.runapi.ai/public/samples/voice.mp3'];
            }
            self::assertSame('task_1', $c->$resource->create($params)->id);
            self::assertSame('https://cdn.runapi.ai/public/samples/video.mp4', $c->$resource->get('task_1')->videos[0]->url);
            self::assertSame('https://cdn.runapi.ai/public/samples/video.mp4', $c->$resource->run($params)->videos[0]->url);
            self::assertSame('POST', $t->requests[0]->getMethod());
            self::assertSame('/api/v1/vidu/'.$path, $t->requests[0]->getUri()->getPath());
            self::assertSame('GET', $t->requests[1]->getMethod());
            self::assertSame('/api/v1/vidu/'.$path.'/task_1', $t->requests[1]->getUri()->getPath());
            self::assertSame($params, json_decode((string)$t->requests[0]->getBody(), true, flags:JSON_THROW_ON_ERROR));
        }
    }
}
