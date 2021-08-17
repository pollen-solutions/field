<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\Select;

interface SelectChoiceInterface
{
    /**
     * Adds a related child choice instance.
     *
     * @param SelectChoiceInterface $selectChoice
     *
     * @return static
     */
    public function addChildren(SelectChoiceInterface $selectChoice): SelectChoiceInterface;

    /**
     * Enables the item.
     *
     * @param bool $enabled
     *
     * @return static
     */
    public function enabled(bool $enabled = true): SelectChoiceInterface;

    /**
     * Gets the list of instances for the related children.
     *
     * @return SelectChoiceInterface[]|array
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
     * @return SelectChoiceInterface|null
     */
    public function getGroup(): ?SelectChoiceInterface;

    /**
     * Gets the label.
     *
     * @return string
     */
    public function getLabel(): string;

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
     * Sets the depth.
     *
     * @param int $depth
     *
     * @return static
     */
    public function setDepth(int $depth = 0): SelectChoiceInterface;

    /**
     * Sets related group instance.
     *
     * @param SelectChoiceInterface $group
     *
     * @return static
     */
    public function setGroup(SelectChoiceInterface $group): SelectChoiceInterface;

    /**
     * Close tag.
     *
     * @return string
     */
    public function tagClose(): string;

    /**
     * Tag content.
     *
     * @return string
     */
    public function tagBody(): string;

    /**
     * Open tag.
     *
     * @return string
     */
    public function tagOpen(): string;
}