<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\RadioCollection;

use RuntimeException;

class RadioChoiceCollection implements RadioChoiceCollectionInterface
{
    /**
     * Current offset of the iterator.
     * @var int
     */
    private int $offset = 0;

    /**
     * @var string|null
     */
    protected ?string $checked = null;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var array
     */
    protected array $radioChoiceDefs = [];

    /**
     * @var RadioChoiceInterface[]
     */
    protected array $radioChoices = [];

    /**
     * @var string[]
     */
    protected array $values = [];

    /**
     * @param array $radioChoiceDefs
     */
    public function __construct(array $radioChoiceDefs = [])
    {
        $this->radioChoiceDefs = $radioChoiceDefs;
    }

    /**
     * @inheritdoc
     */
    public function addChoice(RadioChoiceInterface $radioChoice): RadioChoiceCollectionInterface
    {
        if (!$radioChoice->isGroup()) {
            $value = $radioChoice->getValue();
            if (in_array($radioChoice->getValue(), $this->values, true)) {
                throw new RuntimeException(
                    sprintf(
                        'RadioChoice value [%s] already defined to another',
                        $value
                    )
                );
            }
            $this->values[] = $value;
        }

        $this->radioChoices[$radioChoice->getDepth()][] = $radioChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setChecked($checked): RadioChoiceCollectionInterface
    {
        if (is_scalar($checked)) {
            $this->checked = (string)$checked;
        } elseif ($checked !== null) {
            throw new RuntimeException('CheckedRadio type must be scalar.');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): RadioChoiceCollectionInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function walk(
        ?array $radioChoiceDefs = null,
        int $depth = 0,
        ?RadioChoiceInterface $group = null
    ): RadioChoiceCollectionInterface {
        if (is_null($radioChoiceDefs)) {
            $radioChoiceDefs = $this->radioChoiceDefs;
        }

        foreach ($radioChoiceDefs as $k => $radioChoiceDef) {
            if (!$radioChoiceDef instanceof RadioChoiceInterface) {
                if (is_scalar($radioChoiceDef)) {
                    $radioChoice = new RadioChoice(!is_string($k) ? $radioChoiceDef : $k, $radioChoiceDef);
                } elseif (is_array($radioChoiceDef)) {
                    $radioChoice = new RadioChoice($k, $k, true);

                    $this->walk($radioChoiceDef, $depth + 1, $radioChoice);
                } else {
                    throw new RuntimeException(
                        sprintf(
                            'RadioChoice type must be scalar, array or instance of %s.',
                            RadioChoiceInterface::class
                        )
                    );
                }
            } else {
                $radioChoice = $radioChoiceDef;
            }

            $radioChoice->setName($this->name)->setDepth($depth);

            if ($radioChoice->getValue() === $this->checked) {
                $radioChoice->enabled();
            }

            if ($group) {
                $group->addChildren($radioChoice);
            }

            $this->addChoice($radioChoice);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->radioChoices[0][$this->offset]);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->radioChoices[0][$this->offset] ?? null;
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