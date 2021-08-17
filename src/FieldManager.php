<?php

declare(strict_types=1);

namespace Pollen\Field;

use Exception;
use InvalidArgumentException;
use Pollen\Field\Drivers\InputDriver;
use Pollen\Support\Concerns\ResourcesAwareTrait;
use Pollen\Support\Proxy\RouterProxy;
use Pollen\Field\Drivers\ButtonDriver;
use Pollen\Field\Drivers\CheckboxCollectionDriver;
use Pollen\Field\Drivers\CheckboxDriver;
use Pollen\Field\Drivers\FileDriver;
use Pollen\Field\Drivers\HiddenDriver;
use Pollen\Field\Drivers\LabelDriver;
use Pollen\Field\Drivers\NumberDriver;
use Pollen\Field\Drivers\PasswordDriver;
use Pollen\Field\Drivers\RadioCollectionDriver;
use Pollen\Field\Drivers\RadioDriver;
use Pollen\Field\Drivers\RequiredDriver;
use Pollen\Field\Drivers\SelectDriver;
use Pollen\Field\Drivers\SubmitDriver;
use Pollen\Field\Drivers\TextareaDriver;
use Pollen\Field\Drivers\TextDriver;
use Pollen\Http\ResponseInterface;
use Pollen\Routing\RouteInterface;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Exception\ManagerRuntimeException;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\Routing\Exception\NotFoundException;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

class FieldManager implements FieldManagerInterface
{
    use BootableTrait;
    use ConfigBagAwareTrait;
    use ResourcesAwareTrait;
    use ContainerProxy;
    use RouterProxy;

    /**
     * Field manager main instance.
     * @var FieldManagerInterface|null
     */
    private static ?FieldManagerInterface $instance = null;

    /**
     * List of default registered drivers classname.
     * @var array<string, string>
     */
    private array $defaultDrivers = [
        'button'              => ButtonDriver::class,
        'checkbox'            => CheckboxDriver::class,
        'checkbox-collection' => CheckboxCollectionDriver::class,
        'file'                => FileDriver::class,
        'hidden'              => HiddenDriver::class,
        'input'               => InputDriver::class,
        'label'               => LabelDriver::class,
        'number'              => NumberDriver::class,
        'password'            => PasswordDriver::class,
        'radio'               => RadioDriver::class,
        'radio-collection'    => RadioCollectionDriver::class,
        'required'            => RequiredDriver::class,
        'select'              => SelectDriver::class,
        'submit'              => SubmitDriver::class,
        'text'                => TextDriver::class,
        'textarea'            => TextareaDriver::class
    ];

    /**
     * List of registered driver instances by alias and index.
     * @var array<string, array<string, FieldDriverInterface>>|array
     */
    private $drivers = [];

    /**
     * List of registered driver definitions by alias.
     * @var array<string, FieldDriverInterface|callable|string>|array
     */
    public $driverDefinitions = [];

    /**
     * List of routes instance by HTTP method.
     * @var array<string, RouteInterface>|array
     */
    protected array $routes = [];

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if ($container !== null) {
            $this->setContainer($container);
        }

        $this->setResourcesBaseDir(dirname(__DIR__) . '/resources');

        $this->boot();

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * Retrieves the field manager main instance.
     *
     * @return static
     */
    public static function getInstance(): FieldManagerInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new ManagerRuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->drivers;
    }

    /**
     * @inheritDoc
     */
    public function boot(): FieldManagerInterface
    {
        if (!$this->isBooted()) {
            if ($router = $this->router()) {
                $this->routes['get'] = $router->get(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['post'] = $router->post(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['put'] = $router->put(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['patch'] = $router->patch(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['options'] = $router->options(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['delete'] = $router->delete(
                    '/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
                $this->routes['api'] = $router->xhr(
                    '/api/_field/{field}/{controller}',
                    [$this, 'httpRequestDispatcher']
                );
            }

            $this->registerDefaultDrivers();

            $this->setBooted();
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $alias, $idOrParams = null, ?array $params = []): ?FieldDriverInterface
    {
        if (is_array($idOrParams)) {
            $params = (array)$idOrParams;
            $id = null;
        } else {
            $id = $idOrParams;
        }

        if ($id !== null && isset($this->drivers[$alias][$id])) {
            return $this->drivers[$alias][$id];
        }

        if (!$driver = $this->resolveDriverFromDefinition($alias)) {
            return null;
        }

        $this->drivers[$alias] = $this->drivers[$alias] ?? [];
        $index = count($this->drivers[$alias]);
        $id = $id ?? $alias . $index;
        if (!$driver->getAlias()) {
            $driver->setAlias($alias);
        }

        $params = array_merge($driver->defaultParams(), $this->config("driver.$alias", []), $params ?: []);

        $driver->setIndex($index)->setId($id)->setParams($params);
        $driver->boot();

        return $this->drivers[$alias][$id] = $driver;
    }

    /**
     * @inheritDoc
     */
    public function getRouteUrl(
        string $field,
        ?string $controller = null,
        array $params = [],
        ?string $httpMethod = null
    ): ?string {
        if (!$this->router()) {
            return null;
        }

        $route = $this->routes[$httpMethod ?? 'api'] ?? null;

        if (!$route instanceof RouteInterface) {
            if ($httpMethod === null || $httpMethod === 'api') {
                throw new RuntimeException(sprintf(
                    'The api route for route for the [%s] field driver is not available.', $field
                ));
            } else {
                throw new RuntimeException(sprintf(
                    'The web route for HTTP method [%s] the [%s] field driver is not available.',
                    $field,
                    $httpMethod
                ));
            }
        }

        $controller = $controller ?? 'responseController';

        return $this->router()->getRouteUrl($route, array_merge($params, compact('field', 'controller')));
    }

    /**
     * @inheritDoc
     */
    public function httpRequestDispatcher(string $field, string $controller, ...$args): ResponseInterface
    {
        try {
            $driver = $this->get($field);
        } catch (Exception $e) {
            throw new NotFoundException(
                sprintf('FieldDriver [%s] return exception : %s.', $field, $e->getMessage()),
                'FieldDriver Error',
                $e
            );
        }

        if ($driver !== null) {
            try {
                return $driver->{$controller}(...$args);
            } catch (Exception $e) {
                throw new NotFoundException(
                    sprintf('FieldDriver [%s] Controller [%s] call return exception.', $controller, $field),
                    'FieldDriver Error',
                    $e
                );
            }
        }

        throw new NotFoundException(
            sprintf('FieldDriver [%s] unreachable.', $field),
            'FieldDriver Error'
        );
    }

    /**
     * @inheritDoc
     */
    public function register(
        string $alias,
        $driverDefinition = null,
        ?callable $registerCallback = null
    ): FieldManagerInterface {
        $this->driverDefinitions[$alias] = $driverDefinition ?? InputDriver::class;

        if ($registerCallback !== null) {
            $registerCallback($this);
        }
        return $this;
    }

    /**
     * Default drivers registration.
     *
     * @return static
     */
    protected function registerDefaultDrivers(): FieldManagerInterface
    {
        foreach ($this->defaultDrivers as $alias => $driverDefinition) {
            $this->register($alias, $driverDefinition);
        }
        return $this;
    }

    /**
     * Resolves the driver instance related of a driver definition from its alias.
     *
     * @param string $alias
     *
     * @return FieldDriverInterface|null
     */
    protected function resolveDriverFromDefinition(string $alias): ?FieldDriverInterface
    {
        if (!$def = $this->driverDefinitions[$alias] ?? null) {
            throw new InvalidArgumentException(sprintf('Field with alias [%s] unavailable', $alias));
        }

        $driver = null;

        if ($def instanceof FieldDriverInterface) {
            $driver = clone $def;
        } elseif (is_string($def) && !is_callable($def)) {
            if ($this->containerHas($def)) {
                $driver = clone $this->containerGet($def);
            } elseif (class_exists($def)) {
                $driver = new $def($this);
            }
        } elseif (is_callable($def)) {
            $driver = new CallableFieldDriver($def);
        }

        if ($driver instanceof FieldDriverInterface) {
            $driver->setFieldManager($this);

            return $driver;
        }

        return null;
    }
}