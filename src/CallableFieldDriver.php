<?php

declare(strict_types=1);

namespace Pollen\Field;

class CallableFieldDriver extends FieldDriver
{
    /**
     * Callback render.
     * @var callable
     */
    private $renderCallback;

    /**
     * @param callable $renderCallback
     * @param FieldManagerInterface|null $fieldManager
     */
    public function __construct(callable $renderCallback, ?FieldManagerInterface $fieldManager = null)
    {
        $this->renderCallback = $renderCallback;

        parent::__construct($fieldManager);
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $renderCallback = $this->renderCallback;

        return $renderCallback($this);
    }
}
