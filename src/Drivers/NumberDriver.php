<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class NumberDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!is_numeric($this->get('attrs.value'))) {
            $this->set('attrs.value', '0');
        }

        $this->set('attrs.type', 'number');

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/number');
    }
}