<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class InputDriver extends FieldDriver
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
                 * Type attribute in HTML tag.
                 * @var string $type
                 */
                'type'    => 'text'
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $type = $this->get('attrs.type', $this->get('type') ?? 'type');
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