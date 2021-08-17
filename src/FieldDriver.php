<?php

declare(strict_types=1);

namespace Pollen\Field;

use Closure;
use InvalidArgumentException;
use Pollen\Field\Drivers\LabelDriver;
use Pollen\Http\Response;
use Pollen\Http\ResponseInterface;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ParamsBagDelegateTrait;
use Pollen\Support\Proxy\FieldProxy;
use Pollen\Support\Proxy\HttpRequestProxy;
use Pollen\Support\Proxy\ViewProxy;
use Pollen\Support\Str;
use Pollen\View\ViewInterface;

abstract class FieldDriver implements FieldDriverInterface
{
    use BootableTrait;
    use FieldProxy;
    use HttpRequestProxy;
    use ParamsBagDelegateTrait;
    use ViewProxy;

    /**
     * Index value in field manager.
     * @var int
     */
    private int $index = 0;

    /**
     * Identifier alias.
     * @var string
     */
    protected string $alias = '';

    /**
     * List of default parameters by field driver alias.
     * @var array<string, array>
     */
    protected static array $defaults = [];

    /**
     * Unique identifier.
     * {@internal {{ alias }}{{ index }} by default.}
     */
    protected string $id = '';

    /**
     * Template view instance.
     * @var ViewInterface|null
     */
    protected ?ViewInterface $view = null;

    /**
     * @param FieldManagerInterface|null $fieldManager
     */
    public function __construct(?FieldManagerInterface $fieldManager = null)
    {
        if ($fieldManager !== null) {
            $this->setFieldManager($fieldManager);
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function after(): void
    {
        echo ($after = $this->get('after')) instanceof Closure ? $after($this) : $after;
    }

    /**
     * @inheritDoc
     */
    public function before(): void
    {
        echo ($before = $this->get('before')) instanceof Closure ? $before($this) : $before;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->isBooted()) {
            $this->parseParams();

            $this->setBooted();
        }
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function defaultParams(): array
    {
        return array_merge(
            static::$defaults,
            [
                /**
                 * Main container HTML tag attributes.
                 * @var array $attrs
                 */
                'attrs'  => [],
                /**
                 * Content displayed after the main container.
                 * @var string|callable $after
                 */
                'after'  => '',
                /**
                 * Content displayed before the main container.
                 * @var string|callable $before
                 */
                'before' => '',
                /**
                 * Name of the field in HTTP request of form submission.
                 * @var string|null $name
                 */
                'name'   => null,
                /**
                 * Value of the field in HTTP request of form submission.
                 * @var string|null $value
                 */
                'value'  => null,
                /**
                 * Field label.
                 * @var string|array|LabelDriver|false|null $label
                 */
                'label' => null,
                /**
                 * Field label position.
                 * @var string $label_position
                 */
                'label_position' => 'before',
                /**
                 * List of parameters of the template view|View instance.
                 * @var array|ViewInterface $view
                 */
                'view' => []
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function getBaseClass(): string
    {
        return 'Field' . Str::studly($this->getAlias());
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->get('attrs.name');
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->get('attrs.value');
    }

    /**
     * @inheritDoc
     */
    public function getRouteUrl(
        ?string $controllerMethod = null,
        array $params = [],
        ?string $httpMethod = null
    ): string {
        return $this->field()->getRouteUrl($this->getAlias(), $controllerMethod, $params, $httpMethod);
    }

    /**
     * Echoes the field label.
     *
     * @param string $position after|before
     *
     * @return void
     */
    public function label(string $position = 'before'): void
    {
        if ($this->get('label') === false) {
            return;
        }

        if ($position !== 'after') {
            $position = 'before';
        }

        if ($position !== $this->get('label_position')) {
            return;
        }

        $label = $this->get('label') ?: $this->get('name');
        if (empty($label)) {
            return;
        }

        if ($label instanceof LabelDriver) {
            echo $label;
        }

        $params = [
            'attrs' => [],
        ];

        if (is_string($label)) {
            $params['content'] = $label;
        } elseif (is_array($label)) {
            $params = array_merge($params, $label);
        } else {
            return;
        }

        if (empty($params['attrs']['for']) && ($for = $this->get('attrs.id'))) {
            $params['attrs']['for'] = $for;
        }

        echo $this->field()->get('label', $params);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        $this->parseAttrId()->parseAttrClass()->parseAttrName()->parseAttrValue();
    }

    /**
     * @inheritDoc
     */
    public function parseAttrClass(): FieldDriverInterface
    {
        $base = $this->getBaseClass();

        $default_class = "$base $base--" . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class'), $default_class));
        }

        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrId(): FieldDriverInterface
    {
        if ($this->get('attrs.id') === false) {
            $this->forget('attrs.id');
        } elseif (!$this->get('attrs.id')) {
            $this->set('attrs.id', 'Field' . Str::studly($this->getAlias()) . '--' . $this->getIndex());
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrName(): FieldDriverInterface
    {
        if ($name = $this->pull('name')) {
            $this->set('attrs.name', $name);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrValue(): FieldDriverInterface
    {
        $value = $this->pull('value')?? $this->get('attrs.value');

        $this->set('attrs.value', $value !== null ? (string)$value : null);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->view($this->get('view.template_name') ?? 'index', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function setAlias(string $alias): FieldDriverInterface
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function setDefaults(array $defaults = []): void
    {
        self::$defaults[__CLASS__] = $defaults;
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): FieldDriverInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(int $index): FieldDriverInterface
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if ($this->view === null) {
            $this->view = $this->viewResolver();
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): ?string
    {
        return null;
    }

    /**
     * Resolves view instance.
     *
     * @return ViewInterface
     */
    protected function viewResolver(): ViewInterface
    {
        $default = $this->field()->config('view', []);
        $viewDef = $this->get('view');

        if (!$viewDef instanceof ViewInterface) {
            $directory = $this->get('view.directory');
            if ($directory && !file_exists($directory)) {
                $directory = null;
            }

            $overrideDir = $this->get('view.override_dir');
            if ($overrideDir && !file_exists($overrideDir)) {
                $overrideDir = null;
            }

            if ($directory === null && isset($default['directory'])) {
                $default['directory'] = rtrim($default['directory'], '/') . '/' . $this->getAlias();
                if (file_exists($default['directory'])) {
                    $directory = $default['directory'];
                }
            }

            if ($overrideDir === null && isset($default['override_dir'])) {
                $default['override_dir'] = rtrim($default['override_dir'], '/') . '/' . $this->getAlias();
                if (file_exists($default['override_dir'])) {
                    $overrideDir = $default['override_dir'];
                }
            }

            if ($directory === null) {
                $directory = $this->viewDirectory();
                if (!file_exists($directory)) {
                    throw new InvalidArgumentException(
                        sprintf('Field [%s] must have an accessible view directory.', $this->getAlias())
                    );
                }
            }

            $view = $this->viewManager()->createView('plates')
                ->setDirectory($directory);

            if ($overrideDir !== null) {
                $view->setOverrideDir($overrideDir);
            }
        } else {
            $view = $viewDef;
        }

        $functions = [
            'after',
            'before',
            'getAlias',
            'getId',
            'getIndex',
            'getName',
            'getValue',
            'label'
        ];
        foreach ($functions as $fn) {
            $view->addExtension($fn, [$this, $fn]);
        }

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function responseController(...$args): ResponseInterface
    {
        return new Response(null, 404);
    }
}