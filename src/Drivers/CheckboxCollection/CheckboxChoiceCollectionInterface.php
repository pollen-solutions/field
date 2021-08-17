<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\CheckboxCollection;

use Iterator;

interface CheckboxChoiceCollectionInterface extends Iterator
{
    /**
     * Adds a choice instance in the list of registered.
     *
     * @param CheckboxChoiceInterface $checkboxChoice
     *
     * @return static
     */
    public function addChoice(CheckboxChoiceInterface $checkboxChoice): CheckboxChoiceCollectionInterface;

    /**
     * Sets one or more checked item.
     *
     * @param string|int|array $checked
     *
     * @return static
     */
    public function setChecked($checked): CheckboxChoiceCollectionInterface;

    /**
     * Sets the name attribute of HTML tag for items.
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): CheckboxChoiceCollectionInterface;

    /**
     * Iterator.
     *
     * @param array|null $checkboxChoiceDefs
     * @param int $depth
     * @param CheckboxChoiceInterface|null $group
     *
     * @return CheckboxChoiceCollectionInterface
     */
    public function walk(
        ?array $checkboxChoiceDefs = null,
        int $depth = 0,
        ?CheckboxChoiceInterface $group = null
    ): CheckboxChoiceCollectionInterface;
}