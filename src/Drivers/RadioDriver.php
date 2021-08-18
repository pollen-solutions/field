<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;
use Pollen\Field\FieldDriverInterface;

class RadioDriver extends FieldDriver
{
    /**
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
                 * Valeur de sélection du bouton radio.
                 * @var string $checked
                 */
                'checked' => 'on',
            ]
        );
    }

    /**
     * Checks if the supplied value and the selection value are the same.
     *
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->getValue() === $this->checkedValue;
    }

    /**
     * @inheritDoc
     */
    public function parseAttrValue(): FieldDriverInterface
    {
        $value = $this->pull('value');
        if ($value !== null) {
            $this->setCheckedValue((string)$value);
        }

        $checked = $this->pull('checked');
        if ($checked !== null) {
            $this->set('attrs.value', (string)$checked);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set('attrs.type', 'radio');

        if ($this->isChecked()) {
            $this->push('attrs', 'checked');
        }
        
        return parent::render();
    }

    /**
     * Sets the checked value.
     *
     * @param string $checkedValue
     *
     * @return static
     */
    public function setCheckedValue(string $checkedValue): self
    {
        $this->checkedValue = $checkedValue;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/radio');
    }
}