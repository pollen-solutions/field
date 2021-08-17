<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\Select;

class SelectChoice implements SelectChoiceInterface
{
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
     * @var SelectChoiceInterface|null
     */
    protected ?SelectChoiceInterface $group;

    /**
     * @var SelectChoiceInterface[]|array
     */
    protected array $children = [];

    /**
     * @var bool
     */
    protected bool $enabled = false;

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
    public function addChildren(SelectChoiceInterface $selectChoice): SelectChoiceInterface
    {
        $this->children[] = $selectChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function enabled(bool $enabled = true): SelectChoiceInterface
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
    public function getGroup(): ?SelectChoiceInterface
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
    public function isGroup(): bool
    {
        return $this->isGroup;
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
    public function setDepth(int $depth = 0): SelectChoiceInterface
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setGroup(SelectChoiceInterface $group): SelectChoiceInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tagClose(): string
    {
        return $this->isGroup() ? "\n" . str_repeat("\t", $this->depth) . "</optgroup>" : "</option>";
    }

    /**
     * @inheritdoc
     */
    public function tagBody(): string
    {
        return $this->isGroup() ? '' : $this->getLabel();
    }

    /**
     * @inheritdoc
     */
    public function tagOpen(): string
    {
        $attrs = '';
        if ($this->isGroup()) {
            $attrs .= "label=\"" . $this->getLabel() . "\"";
        } else {
            $attrs .= "value=\"" . $this->getValue() . "\"";
            if ($this->isEnabled()) {
                $attrs .= " selected";
            }
        }

        return "\n" .
            str_repeat("\t", $this->getDepth()) .
            ($this->isGroup()
                ? "<optgroup $attrs>" : "<option $attrs>"
            );
    }
}