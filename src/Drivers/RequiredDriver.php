<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class RequiredDriver extends FieldDriver
{
    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/required');
    }
}
