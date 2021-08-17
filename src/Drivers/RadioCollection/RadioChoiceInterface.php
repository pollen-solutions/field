<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\RadioCollection;

interface RadioChoiceInterface
{
    /**
     * Adds a related child choice instance.
     *
     * @param RadioChoiceInterface $radioChoice
     *
     * @return static
     */
    public function addChildren(RadioChoiceInterface $radioChoice): RadioChoiceInterface;

    /**
     * Enables the item.
     *
     * @param bool $enabled
     *
     * @return static
     */
    public function enabled(bool $enabled = true): RadioChoiceInterface;

    /**
     * Gets the list of instances for the related children.
     *
     * @return RadioChoiceInterface[]|array
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
     * @return RadioChoiceInterface|null
     */
    public function getGroup(): ?RadioChoiceInterface;

    /**
     * Gets the label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Gets the name attribute in the HTML tag.
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
    public function setDepth(int $depth = 0): RadioChoiceInterface;

    /**
     * Sets related group instance.
     *
     * @param RadioChoiceInterface $group
     *
     * @return static
     */
    public function setGroup(RadioChoiceInterface $group): RadioChoiceInterface;

    /**
     * Sets the name attribute of HTML tag.
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): RadioChoiceInterface;
}