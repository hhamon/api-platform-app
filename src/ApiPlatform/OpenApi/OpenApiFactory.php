<?php

declare(strict_types=1);

namespace App\ApiPlatform\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\SecurityScheme;
use ApiPlatform\OpenApi\OpenApi;
use ArrayObject;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('api_platform.openapi.factory')]
final readonly class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $openApiFactory,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);

        $securitySchemes = $openApi->getComponents()->getSecuritySchemes();

        if ($securitySchemes instanceof ArrayObject) {
            $securitySchemes['basic_auth'] = new SecurityScheme(
                type: 'http',
                description: 'Authenticate as HTTP basic with your account email address and password.',
                scheme: 'basic',
            );
        }

        return $openApi;
    }
}
