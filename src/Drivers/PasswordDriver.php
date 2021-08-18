<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class PasswordDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set('attrs.type', 'password');

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/password');
    }
}