<?php

declare(strict_types=1);

namespace Pollen\Field\Drivers;

use Pollen\Field\FieldDriver;

class ButtonDriver extends FieldDriver
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
                 * Content of HTML button tag.
                 * @var string $content
                 */
                'content' => 'Send',
                /**
                 * Button type in HTML tag.
                 * @var string $type
                 */
                'type'    => 'button',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->has('attrs.type')) {
            $this->set('attrs.type', $this->get('type', 'button'));
        }
        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->field()->resources('/views/button');
    }
}