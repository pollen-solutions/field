<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class TextareaDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        $this->parseAttrId()->parseAttrClass()->parseAttrName();
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set('content', $this->get('value'));

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/textarea');
    }
}