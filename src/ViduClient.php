<?php

declare(strict_types=1);

namespace RunApi\Vidu;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Vidu\Resources\ImageToVideo;
use RunApi\Vidu\Resources\ReferenceToVideo;

final class ViduClient extends BaseClient
{
    public readonly ImageToVideo $imageToVideo;
    public readonly ReferenceToVideo $referenceToVideo;
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->imageToVideo = ImageToVideo::fromHttp($this->http);
        $this->referenceToVideo = ReferenceToVideo::fromHttp($this->http);
    }
}
