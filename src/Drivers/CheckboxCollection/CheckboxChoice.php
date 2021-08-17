<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\CheckboxCollection;

use Pollen\Field\Drivers\CheckboxDriver;
use Pollen\Support\Proxy\FieldProxy;
use Throwable;

class CheckboxChoice implements CheckboxChoiceInterface
{
    use FieldProxy;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var string
     */
    protected string $value = '';

    /**
     * @var string
     */
    protected string $label = '';

    /**
     * @var bool
     */
    protected bool $isGroup = false;

    /**
     * @var int
     */
    protected int $depth = 0;

    /**
     * @var CheckboxChoiceInterface|null
     */
    protected ?CheckboxChoiceInterface $group = null;

    /**
     * @var CheckboxChoiceInterface[]|array
     */
    protected array $children = [];

    /**
     * @var string
     */
    protected $enabled = false;

    /**
     * @param string $value
     * @param string|null $label
     * @param bool $isGroup
     */
    public function __construct(string $value, ?string $label = null, bool $isGroup = false)
    {
        $this->value = $value;
        $this->label = $label ?? $this->value;
        $this->isGroup = $isGroup;
    }

    /**
     * @inheritdoc
     */
    public function addChildren(CheckboxChoiceInterface $checkboxChoice): CheckboxChoiceInterface
    {
        $this->children[] = $checkboxChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function enabled(bool $enabled = true): CheckboxChoiceInterface
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getGroup(): ?CheckboxChoiceInterface
    {
        return $this->group;
    }

    /**
     * @inheritdoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function inGroup(): bool
    {
        return $this->group !== null;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @inheritdoc
     */
    public function isGroup(): bool
    {
        return $this->isGroup;
    }

    /**
     * @inheritdoc
     */
    public function render(): string
    {
        if ($this->isGroup()) {
            return $this->getLabel();
        }

        try {
            /** @var CheckboxDriver $field */
            $field = $this->field(
                'checkbox',
                [
                    'name'    => $this->getName(),
                    'value'   => $this->isEnabled() ? $this->getValue() : null,
                    'label'   => $this->getLabel(),
                    'checked' => $this->getValue(),
                ]
            );

            if ($field instanceof CheckboxDriver) {
                return $field->render();
            }

            return '';
        } catch (Throwable $e) {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function setDepth(int $depth = 0): CheckboxChoiceInterface
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setGroup(CheckboxChoiceInterface $group): CheckboxChoiceInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): CheckboxChoiceInterface
    {
        $this->name = $name;

        return $this;
    }
}