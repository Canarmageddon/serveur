<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated){

    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string'
                ],
                'password' => [
                    'type' => 'string'
                ]
            ]
        ]);
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string'
                ],
                'refresh_token' => [
                    'type' => 'string'
                ]
            ]
        ]);
        $schemas['RefToken'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
                    'type' => 'string'
                ]
            ]
        ]);

        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',
                tags: ['Auth'],
                responses: [
                    '200' => [
                        'description' => 'User logged in',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token'
                                ]
                            ]
                        ]
                    ],
                    '401' => [
                        'description' => 'Invalid credentials'
                    ]
                ],
                summary: 'Login using credentials',
                description: 'Login using credentials',
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/login', $pathItem);

        $pathItem = new PathItem(
            get: new Operation(
                operationId: 'whoami',
                tags: ['Auth'],
                security: [['bearerAuth' => []]],
                responses: [
                    '200' => [
                        'description' => 'Current logged as: {user or null}',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-user.item'
                                ]
                            ]
                        ]
                    ]
                ],
                summary: 'Get user you are logged as',
                description: 'Get user you are logged as'
            )
        );
        $openApi->getPaths()->addPath('/api/whoami', $pathItem);

        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'refreshToken',
                tags: ['Auth'],
                responses: [
                    '200' => [
                        'description' => 'New access token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token'
                                ]
                            ]
                        ]
                    ],
                    '401' => [
                        'description' => 'Invalid refresh token'
                    ]
                ],
                summary: 'Refresh access token using refresh_token',
                description: 'Refresh access token using refresh_token',
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefToken'
                            ]
                        ]
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/token/refresh', $pathItem);
        return $openApi;
    }
}