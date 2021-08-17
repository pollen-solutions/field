<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\RadioCollection;

use Iterator;

interface RadioChoiceCollectionInterface extends Iterator
{
    /**
     * Adds a choice instance in the list of registered.
     *
     * @param RadioChoiceInterface $radioChoice
     *
     * @return static
     */
    public function addChoice(RadioChoiceInterface $radioChoice): RadioChoiceCollectionInterface;

    /**
     * Sets one or more checked item.
     *
     * @param string|int|array $checked
     *
     * @return static
     */
    public function setChecked($checked): RadioChoiceCollectionInterface;

    /**
     * Sets the name attribute of HTML tag for items.
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): RadioChoiceCollectionInterface;

    /**
     * Iterator.
     *
     * @param array|null $radioChoiceDefs
     * @param int $depth
     * @param RadioChoiceInterface|null $group
     *
     * @return RadioChoiceCollectionInterface
     */
    public function walk(
        ?array $radioChoiceDefs = null,
        int $depth = 0,
        ?RadioChoiceInterface $group = null
    ): RadioChoiceCollectionInterface;
}