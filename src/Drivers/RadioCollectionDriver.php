<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriverInterface;
use Pollen\Field\Drivers\RadioCollection\RadioChoiceInterface;
use Pollen\Field\Drivers\RadioCollection\RadioChoiceCollection;
use Pollen\Field\Drivers\RadioCollection\RadioChoiceCollectionInterface;
use Pollen\Field\FieldDriver;

class RadioCollectionDriver extends FieldDriver
{
    /**
     * Checked value.
     * @var string|null
     */
    protected ?string $checkedValue = null;

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                /**
                 * @var array|RadioDriver[]|RadioChoiceInterface[]|RadioChoiceCollectionInterface $choices
                 */
                'choices' => [],
            ]
        );
    }

    /**
     * Gets the checked value.
     *
     * @return string|null
     */
    public function getCheckedValue(): ?string
    {
        return $this->checkedValue;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrName(): FieldDriverInterface
    {
        if ($name = $this->pull('name')) {
            $this->set('radios.attrs.name', $name);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrValue(): FieldDriverInterface
    {
        $value = $this->pull('value');
        $this->checkedValue = $value !== null ? (string)$value : null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $choices = $this->get('choices', []);
        if (!$choices instanceof RadioChoiceCollectionInterface) {
            $this->set('choices', $choices = new RadioChoiceCollection($choices));
        }
        $choices->setName($this->get('radios.attrs.name'))->setChecked($this->getCheckedValue())->walk();

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/radio-collection');
    }
}