<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\Select;

use RuntimeException;

class SelectChoiceCollection implements SelectChoiceCollectionInterface
{
    /**
     * Current offset of the iterator.
     * @var int
     */
    private int $offset = 0;

    /**
     * @var array
     */
    protected array $selectChoiceDefs = [];

    /**
     * @var SelectChoiceInterface[]
     */
    protected array $selectChoices = [];

    /**
     * @var array
     */
    protected array $selected = [];

    /**
     * @var string[]
     */
    protected array $values = [];

    /**
     * @param array $selectChoiceDefs
     */
    public function __construct(array $selectChoiceDefs = [])
    {
        $this->selectChoiceDefs = $selectChoiceDefs;
    }

    /**
     * @inheritdoc
     */
    public function addChoice(SelectChoiceInterface $selectChoice): SelectChoiceCollectionInterface
    {
        if (!$selectChoice->isGroup()) {
            $value = $selectChoice->getValue();
            if (in_array($selectChoice->getValue(), $this->values, true)) {
                throw new RuntimeException(
                    sprintf(
                        'SelectChoice value [%s] already defined to another.',
                        $value
                    )
                );
            }
            $this->values[] = $value;
        }

        $this->selectChoices[$selectChoice->getDepth()][] = $selectChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSelected($selected): SelectChoiceCollectionInterface
    {
        if (is_scalar($selected)) {
            $this->selected = [(string)$selected];
        } elseif (is_array($selected)) {
            $this->selected = array_map('strval', $selected);
        } elseif ($selected !== null) {
            throw new RuntimeException('SelectedChoice type must be scalar or array.');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function walk(
        ?array $selectChoiceDefs = null,
        int $depth = 0,
        ?SelectChoiceInterface $optGroup = null
    ): SelectChoiceCollectionInterface {
        if (is_null($selectChoiceDefs)) {
            $selectChoiceDefs = $this->selectChoiceDefs;
        }

        foreach ($selectChoiceDefs as $k => $selectChoiceDef) {
            if (!$selectChoiceDef instanceof SelectChoiceInterface) {
                if (is_scalar($selectChoiceDef)) {
                    $selectChoice = new SelectChoice((string)$k, $selectChoiceDef);
                } elseif (is_array($selectChoiceDef)) {
                    $selectChoice = new SelectChoice((string)$k, $k, true);

                    $this->walk($selectChoiceDef, $depth + 1, $selectChoice);
                } else {
                    throw new RuntimeException(
                        sprintf(
                            'SelectChoice type must be scalar, array or instance of %s',
                            SelectChoiceInterface::class
                        )
                    );
                }
            } else {
                $selectChoice = $selectChoiceDef;
            }

            $selectChoice->setDepth($depth);
            if (in_array($selectChoice->getValue(), $this->selected, true)) {
                $selectChoice->enabled();
            }

            if ($optGroup) {
                $optGroup->addChildren($selectChoice);
            }

            $this->addChoice($selectChoice);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->selectChoices[0][$this->offset]);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->selectChoices[0][$this->offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        return $this->offset++;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->offset = 0;
    }
}