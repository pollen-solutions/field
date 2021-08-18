<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class LabelDriver extends FieldDriver
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
                 * Label HTML content.
                 * @var string $content
                 */
                'content' => '',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function label(string $position = 'before'): void
    {
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/label');
    }
}