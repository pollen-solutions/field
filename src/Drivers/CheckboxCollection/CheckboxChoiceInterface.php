<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\CheckboxCollection;

interface CheckboxChoiceInterface
{
    /**
     * Adds a related child choice instance.
     *
     * @param CheckboxChoiceInterface $checkboxChoice
     *
     * @return static
     */
    public function addChildren(CheckboxChoiceInterface $checkboxChoice): CheckboxChoiceInterface;

    /**
     * Enables the item.
     *
     * @param bool $enabled
     *
     * @return static
     */
    public function enabled(bool $enabled = true): CheckboxChoiceInterface;

    /**
     * Gets the list of instances for the related children.
     *
     * @return CheckboxChoiceInterface[]|array
     */
    public function getChildren(): array;

    /**
     * Gets the depth.
     *
     * @return int
     */
    public function getDepth(): int;

    /**
     * Gets the instance of the related group.
     *
     * @return CheckboxChoiceInterface|null
     */
    public function getGroup(): ?CheckboxChoiceInterface;

    /**
     * Gets the label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Gets the name attribute in the HTML tag.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Gets the value attribute in the HTML tag.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Checks if is in a group.
     *
     * @return bool
     */
    public function inGroup(): bool;

    /**
     * Checks if it is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Checks if it is a group.
     *
     * @return bool
     */
    public function isGroup(): bool;

    /**
     * Render.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Sets the depth.
     *
     * @param int $depth
     *
     * @return static
     */
    public function setDepth(int $depth = 0): CheckboxChoiceInterface;

    /**
     * Sets related group instance.
     *
     * @param CheckboxChoiceInterface $group
     *
     * @return static
     */
    public function setGroup(CheckboxChoiceInterface $group): CheckboxChoiceInterface;

    /**
     * Sets the name attribute of HTML tag.
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): CheckboxChoiceInterface;
}