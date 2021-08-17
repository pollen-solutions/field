<?php

declare(strict_types=1);

namespace Pollen\Field;

use Pollen\Container\BootableServiceProvider;
use Pollen\Field\Drivers\ButtonDriver;
use Pollen\Field\Drivers\CheckboxDriver;
use Pollen\Field\Drivers\CheckboxCollectionDriver;
use Pollen\Field\Drivers\FileDriver;
use Pollen\Field\Drivers\HiddenDriver;
use Pollen\Field\Drivers\InputDriver;
use Pollen\Field\Drivers\LabelDriver;
use Pollen\Field\Drivers\NumberDriver;
use Pollen\Field\Drivers\PasswordDriver;
use Pollen\Field\Drivers\RadioDriver;
use Pollen\Field\Drivers\RadioCollectionDriver;
use Pollen\Field\Drivers\RequiredDriver;
use Pollen\Field\Drivers\SelectDriver;
use Pollen\Field\Drivers\SubmitDriver;
use Pollen\Field\Drivers\TextDriver;
use Pollen\Field\Drivers\TextareaDriver;

class FieldServiceProvider extends BootableServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        FieldManagerInterface::class,
        ButtonDriver::class,
        CheckboxDriver::class,
        CheckboxCollectionDriver::class,
        FileDriver::class,
        HiddenDriver::class,
        InputDriver::class,
        LabelDriver::class,
        NumberDriver::class,
        PasswordDriver::class,
        RadioDriver::class,
        RadioCollectionDriver::class,
        RequiredDriver::class,
        SelectDriver::class,
        SubmitDriver::class,
        TextDriver::class,
        TextareaDriver::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(
            FieldManagerInterface::class,
            function () {
                return new FieldManager([], $this->getContainer());
            }
        );

        $this->registerDrivers();
    }

    /**
     * Register default driver services.
     *
     * @return void
     */
    public function registerDrivers(): void
    {
        $this->getContainer()->add(
            ButtonDriver::class,
            function () {
                return new ButtonDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            CheckboxDriver::class,
            function () {
                return new CheckboxDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            CheckboxCollectionDriver::class,
            function () {
                return new CheckboxCollectionDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            FileDriver::class,
            function () {
                return new FileDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            HiddenDriver::class,
            function () {
                return new HiddenDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            InputDriver::class,
            function () {
                return new InputDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            LabelDriver::class,
            function () {
                return new LabelDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            NumberDriver::class,
            function () {
                return new NumberDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            PasswordDriver::class,
            function () {
                return new PasswordDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            RadioDriver::class,
            function () {
                return new RadioDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            RadioCollectionDriver::class,
            function () {
                return new RadioCollectionDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            RequiredDriver::class,
            function () {
                return new RequiredDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            SelectDriver::class,
            function () {
                return new SelectDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            SubmitDriver::class,
            function () {
                return new SubmitDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            TextDriver::class,
            function () {
                return new TextDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );

        $this->getContainer()->add(
            TextareaDriver::class,
            function () {
                return new TextareaDriver($this->getContainer()->get(FieldManagerInterface::class));
            }
        );
    }
}