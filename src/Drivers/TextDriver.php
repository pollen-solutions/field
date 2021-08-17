<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class TextDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set('attrs.type', 'text');

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/text');
    }
}