<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers\CheckboxCollection;

use RuntimeException;

class CheckboxChoiceCollection implements CheckboxChoiceCollectionInterface
{
    /**
     * Current offset of the iterator.
     * @var int
     */
    private int $offset = 0;

    /**
     * @var array
     */
    protected array $checkboxChoiceDefs = [];

    /**
     * @var CheckboxChoiceInterface[]
     */
    protected array $checkboxChoices = [];

    /**
     * @var string[]
     */
    protected array $checked = [];

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var string[]
     */
    protected array $values = [];

    /**
     * @param array $checkboxChoiceDefs
     */
    public function __construct(array $checkboxChoiceDefs = [])
    {
        $this->checkboxChoiceDefs = $checkboxChoiceDefs;
    }

    /**
     * @inheritdoc
     */
    public function addChoice(CheckboxChoiceInterface $checkboxChoice): CheckboxChoiceCollectionInterface
    {
        if (!$checkboxChoice->isGroup()) {
            $value = $checkboxChoice->getValue();
            if (in_array($checkboxChoice->getValue(), $this->values, true)) {
                throw new RuntimeException(
                    sprintf(
                        'CheckboxChoice value [%s] already defined to another',
                        $value
                    )
                );
            }
            $this->values[] = $value;
        }

        $this->checkboxChoices[$checkboxChoice->getDepth()][] = $checkboxChoice;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setChecked($checked): CheckboxChoiceCollectionInterface
    {
        if (is_scalar($checked)) {
            $this->checked = [(string)$checked];
        } elseif (is_array($checked)) {
            $this->checked = array_map('strval', $checked);
        } elseif ($checked !== null) {
            throw new RuntimeException('CheckedChoice type must be scalar or array.');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): CheckboxChoiceCollectionInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function walk(
        ?array $checkboxChoiceDefs = null,
        int $depth = 0,
        ?CheckboxChoiceInterface $group = null
    ): CheckboxChoiceCollectionInterface {
        if (is_null($checkboxChoiceDefs)) {
            $checkboxChoiceDefs = $this->checkboxChoiceDefs;
        }

        foreach ($checkboxChoiceDefs as $k => $checkboxChoiceDef) {
            if (!$checkboxChoiceDef instanceof CheckboxChoiceInterface) {
                if (is_scalar($checkboxChoiceDef)) {
                    $checkboxChoice = new CheckboxChoice(!is_string($k) ? $checkboxChoiceDef : $k, $checkboxChoiceDef);
                } elseif (is_array($checkboxChoiceDef)) {
                    $checkboxChoice = new CheckboxChoice($k, $k, true);

                    $this->walk($checkboxChoiceDef, $depth + 1, $checkboxChoice);
                } else {
                    throw new RuntimeException(
                        sprintf(
                            'CheckboxChoice type must be scalar, array or instance of %s',
                            CheckboxChoiceInterface::class
                        )
                    );
                }
            } else {
                $checkboxChoice = $checkboxChoiceDef;
            }

            $checkboxChoice->setName($this->name)->setDepth($depth);

            if (in_array($checkboxChoice->getValue(), $this->checked, true)) {
                $checkboxChoice->enabled();
            }

            if ($group) {
                $group->addChildren($checkboxChoice);
            }

            $this->addChoice($checkboxChoice);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->checkboxChoices[0][$this->offset]);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->checkboxChoices[0][$this->offset] ?? null;
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