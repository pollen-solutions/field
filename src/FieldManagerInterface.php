<?php

declare(strict_types=1);

namespace Pollen\Field;

use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Concerns\ResourcesAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;
use Pollen\Support\Proxy\RouterProxyInterface;
use Pollen\Routing\Exception\NotFoundException;

interface FieldManagerInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ResourcesAwareTraitInterface,
    ContainerProxyInterface,
    RouterProxyInterface
{
    /**
     * Returns the list of registered drivers instances.
     *
     * @return array<string, array<string, FieldDriverInterface>>|array
     */
    public function all(): array;

    /**
     * Booting.
     *
     * @return static
     */
    public function boot(): FieldManagerInterface;

    /**
     * Gets a registered driver instance.
     *
     * @param string $alias
     * @param string|array|null $idOrParams
     * @param array|null $params
     *
     * @return FieldDriverInterface|null
     */
    public function get(string $alias, $idOrParams = null, ?array $params = []): ?FieldDriverInterface;

    /**
     * Get the route url of a driver HTTP request handle.
     *
     * @param string $field
     * @param string|null $controller
     * @param array $params
     * @param string|null $httpMethod
     *
     * @return string|null
     */
    public function getRouteUrl(
        string $field,
        ?string $controller = null,
        array $params = [],
        ?string $httpMethod = null
    ): ?string;

    /**
     * HTTP request dispatcher for a field driver.
     *
     * @param string $field
     * @param string $controller
     * @param mixed ...$args
     *
     * @return ResponseInterface
     *
     * @throws NotFoundException
     */
    public function httpRequestDispatcher(string $field, string $controller, ...$args): ResponseInterface;

    /**
     * Register a driver.
     *
     * @param string $alias
     * @param string|FieldDriverInterface|callable|null $driverDefinition
     * @param callable|null $registerCallback
     *
     * @return static
     */
    public function register(
        string $alias,
        $driverDefinition = null,
        ?callable $registerCallback = null
    ): FieldManagerInterface;
}