<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\Drivers\Select\SelectChoiceInterface;
use Pollen\Field\Drivers\Select\SelectChoiceCollection;
use Pollen\Field\Drivers\Select\SelectChoiceCollectionInterface;
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldDriverInterface;

class SelectDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(
            parent::defaultParams(),
            [
                /**
                 * List of selection choices.
                 * @var string[]|array|SelectChoiceInterface[]|SelectChoiceCollectionInterface $choices.
                 */
                'choices'  => [],
                /**
                 * Allow selection of multiple items.
                 * @var bool $multiple
                 */
                'multiple' => false,
                /**
                 * Enable HTML wrapper.
                 * @var bool
                 */
                'wrapper' => false
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        $value = $this->get('attrs.value');

        if ($value === null) {
            return null;
        }

        if (!is_array($value)) {
            $value = array_map('trim', explode(',', (string)$value));
        }

        $value = array_unique($value);

        if (!$this->get('multiple')) {
            $value = [reset($value)];
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrName(): FieldDriverInterface
    {
        if ($name = $this->pull('name')) {
            $this->set('attrs.name', $this->get('multiple') ? $name . '[]' : $name);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $choices = $this->get('choices', []);
        if (!$choices instanceof SelectChoiceCollectionInterface) {
            $this->set('choices', $choices = new SelectChoiceCollection($choices));
        }
        $choices->setSelected($this->getValue())->walk();

        if ($this->get('multiple')) {
            $this->push('attrs', 'multiple');
        }

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/select');
    }
}