<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceInterface;
use Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceCollection;
use Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceCollectionInterface;
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldDriverInterface;
use Pollen\Support\Arr;

class CheckboxCollectionDriver extends FieldDriver
{
    /**
     * List of checked values.
     * @var string[]
     */
    protected array $checkedValues = [];

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                /**
                 * List of checkbox choices.
                 * @var array|CheckboxDriver[]|CheckboxChoiceInterface[]|CheckboxChoiceCollectionInterface $choices
                 */
                'choices' => [],
            ]
        );
    }

    /**
     * Gets list of checked values.
     *
     * @return string[]|null
     */
    public function getCheckedValues(): ?array
    {
        return $this->checkedValues;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrName(): FieldDriverInterface
    {
        if ($name = $this->pull('name')) {
            $this->set('checkboxes.attrs.name', $name . '[]');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrValue(): FieldDriverInterface
    {
        $value = $this->pull('value');
        $this->checkedValues = $value !== null ? Arr::wrap($value) : null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $choices = $this->get('choices', []);
        if (!$choices instanceof CheckboxChoiceCollectionInterface) {
            $this->set('choices', $choices = new CheckboxChoiceCollection($choices));
        }
        $choices->setName($this->get('checkboxes.attrs.name'))->setChecked($this->getCheckedValues())->walk();

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/checkbox-collection');
    }
}