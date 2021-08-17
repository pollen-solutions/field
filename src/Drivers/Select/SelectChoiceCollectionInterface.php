<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\Select;

use Iterator;

interface SelectChoiceCollectionInterface extends Iterator
{
    /**
     * Adds a choice instance in the list of registered.
     *
     * @param SelectChoiceInterface $selectChoice
     *
     * @return static
     */
    public function addChoice(SelectChoiceInterface $selectChoice): SelectChoiceCollectionInterface;

    /**
     * Sets one or more selected item.
     *
     * @param string|int|array $selected
     *
     * @return static
     */
    public function setSelected($selected): SelectChoiceCollectionInterface;

    /**
     * Iterator.
     *
     * @param array|null $selectChoiceDefs
     * @param int $depth
     * @param SelectChoiceInterface|null $optGroup
     *
     * @return SelectChoiceCollectionInterface
     */
    public function walk(
        ?array $selectChoiceDefs = null,
        int $depth = 0,
        ?SelectChoiceInterface $optGroup = null
    ): SelectChoiceCollectionInterface;
}