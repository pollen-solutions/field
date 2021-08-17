<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class InputDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $type = $this->get('attrs.type', $this->get('type', 'text'));
        $this->set('attrs.type', $type);

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/input');
    }
}