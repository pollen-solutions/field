<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class SubmitDriver extends FieldDriver
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
                 * Button text and value in HTTP request form submission.
                 * @var string $value
                 */
                'value' => 'Send',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set('attrs.type', 'submit');

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/submit');
    }
}