<?php

declare(strict_types=1);

namespace Pollen\Field;

use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ParamsBagDelegateTraitInterface;
use Pollen\Support\Proxy\HttpRequestProxyInterface;
use Pollen\Support\Proxy\FieldProxyInterface;
use Pollen\Support\Proxy\ViewProxyInterface;
use Pollen\View\ViewInterface;

interface FieldDriverInterface extends
    BootableTraitInterface,
    HttpRequestProxyInterface,
    FieldProxyInterface,
    ParamsBagDelegateTraitInterface,
    ViewProxyInterface
{
    /**
     * Resolves class as a string and returns the render.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Echoes content render displayed after the main container.
     *
     * @return void
     */
    public function after(): void;

    /**
     * Echoes content render displayed before the main container.
     *
     * @return void
     */
    public function before(): void;

    /**
     * Booting.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Gets alias identifier.
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Gets the base prefix of HTML class.
     *
     * @return string
     */
    public function getBaseClass(): string;

    /**
     * Gets the unique identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Gets the index in related field manager.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * Gets the name in the HTTP request of form submission.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Gets the value in the HTTP request of form submission.
     *
     * @return mixed|null
     */
    public function getValue();

    /**
     * Gets the handle route url.
     *
     * @param string|null $controllerMethod
     * @param array $params
     * @param string|null $httpMethod
     *
     * @return string
     */
    public function getRouteUrl(
        ?string $controllerMethod = null,
        array $params = [],
        ?string $httpMethod = null
    ): string;

    /**
     * Echoes the field label.
     *
     * @param string $position after|before
     *
     * @return void
     */
    public function label(string $position = 'before'): void;

    /**
     * Parse the HTML tag class attribute of main container.
     *
     * @return static
     */
    public function parseAttrClass(): FieldDriverInterface;

    /**
     * Parse the HTML tag id attribute of main container.
     *
     * @return static
     */
    public function parseAttrId(): FieldDriverInterface;

    /**
     * Parse the HTML tag name attribute of the field.
     *
     * @return static
     */
    public function parseAttrName(): FieldDriverInterface;

    /**
     * Parse the HTML tag value attribute of the field.
     *
     * @return static
     */
    public function parseAttrValue(): FieldDriverInterface;

    /**
     * Render.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Sets the alias identifier.
     *
     * @param string $alias
     *
     * @return static
     */
    public function setAlias(string $alias): FieldDriverInterface;

    /**
     * Sets the default parameters.
     *
     * @param array $defaults
     *
     * @return void
     */
    public static function setDefaults(array $defaults = []): void;

    /**
     * Sets the unique identifier.
     *
     * @param string $id
     *
     * @return static
     */
    public function setId(string $id): FieldDriverInterface;

    /**
     * Sets the index in field manager.
     *
     * @param int $index
     *
     * @return static
     */
    public function setIndex(int $index): FieldDriverInterface;

    /**
     * Resolves view instance|returns a particular template render.
     *
     * @param string|null $name.
     * @param array $data
     *
     * @return ViewInterface|string
     */
    public function view(?string $name = null, array $data = []);

    /**
     * Gets the absolute path to the template directory.
     *
     * @return string|null
     */
    public function viewDirectory(): ?string;

    /**
     * Controller method of the route stack.
     *
     * @param array ...$args
     *
     * @return ResponseInterface
     */
    public function responseController(...$args): ResponseInterface;
}