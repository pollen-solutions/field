<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\RadioCollection;

use Pollen\Field\Drivers\RadioDriver;
use Pollen\Support\Proxy\FieldProxy;
use Throwable;

class RadioChoice implements RadioChoiceInterface
{
    use FieldProxy;

    /**
     * @var RadioChoiceInterface[]|array
     */
    protected array $children = [];

    /**
     * @var int
     */
    protected int $depth = 0;

    /**
     * @var bool
     */
    protected bool $enabled = false;

    /**
     * @var RadioChoiceInterface|null
     */
    protected ?RadioChoiceInterface $group = null;

    /**
     * @var bool
     */
    protected bool $isGroup = false;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var string
     */
    protected string $label = '';

    /**
     * @var string
     */
    protected string $value = '';

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
    public function addChildren(RadioChoiceInterface $radioChoice): RadioChoiceInterface
    {
        $this->children[] = $radioChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function enabled(bool $enabled = true): RadioChoiceInterface
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
    public function getGroup(): ?RadioChoiceInterface
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
            /** @var RadioDriver $field */
            $field = $this->field(
                'radio',
                [
                    'name'    => $this->getName(),
                    'value'   => $this->isEnabled() ? $this->getValue() : null,
                    'label'   => $this->getLabel(),
                    'checked' => $this->getValue(),
                ]
            );

            if ($field instanceof RadioDriver) {
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
    public function setDepth(int $depth = 0): RadioChoiceInterface
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setGroup(RadioChoiceInterface $group): RadioChoiceInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): RadioChoiceInterface
    {
        $this->name = $name;

        return $this;
    }
}