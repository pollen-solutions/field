# Field Component

[![Latest Stable Version](https://img.shields.io/packagist/v/pollen-solutions/field.svg?style=for-the-badge)](https://packagist.org/packages/pollen-solutions/field)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen Solutions **Field** Component provides a layer and tools for creating reusable form fields.

## Installation

```bash
composer require pollen-solutions/field
```

### From a callable

```php
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', function () {
    return '<label>Hello : </label><input name="username" placeholder="Please, enter your name">';
});

echo $field->get('hello');
```

### From the default field input driver

```php
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello');

echo $field->get('hello', [
    'attrs' => [
        'name' => 'username',
        'placeholder' => 'Please, enter your name'
    ],
    'label' => 'Hello :'
]);
```

### From a custom driver

```php
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldManager;

class HelloField extends FieldDriver
{
    public function render() : string{
        return '<label>Hello : </label><input name="username" placeholder="Please, enter your name">';
    }
}

$field = new FieldManager();

$field->register('hello', HelloField::class);

echo $field->get('hello');
```

### Through a PSR-11 depency injection container

```php
use Pollen\Container\Container;
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldManager;

$container = new Container();

$field = new FieldManager();
$field->setContainer($container);

class HelloField extends FieldDriver
{
    public function render() : string{
        return '<label>Hello : </label><input name="username" placeholder="Please, enter your name">';
    }
}

$container->add('helloFieldService', HelloField::class);

$field->register('hello', 'helloFieldService');

echo $field->get('hello');
```

### Shows a field driver instance with custom parameters

```php
use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', function (FieldDriverInterface $driver) {
    return '<label>Hello : </label><input name="username" placeholder="' . $driver->get('placeholder') . '">';
});

echo $field->get('hello', ['placeholder' => 'Please, enter your username']);
```

### Recalls the same field driver instance with keeped custom parameters

```php
use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', function (FieldDriverInterface $driver) {
    return '<label>Hello : </label><input name="username" placeholder="' . $driver->get('placeholder') . '">';
});

echo $field->get('hello', 'HelloUsername', ['placeholder' => 'Please, enter your username']);
echo $field->get('hello', 'HelloLogin', ['placeholder' => 'Please, enter your login']);
echo $field->get('hello', 'HelloUsername');
```

## Partial driver API

### Partial driver parameters of call

```php
use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManager;

$field = new FieldManager();

$input = $field->get('input', [
    /** 
     * Common driver parameters.
     * -------------------------------------------------------------------------- 
     */
    /**
     * Main container HTML tag attributes.
     * @var array $attrs
     */
    'attrs'  => [
        'class' => '%s MyAppendedClass'
    ],
    /**
     * Content displayed after the main container.
     * @var string|callable $after
     */
    'after'  => 'content show after',
    /**
     * Content displayed before the main container.
     * @var string|callable $before
     */
    'before'  => function (FieldDriverInterface $driver) {
        return 'content show before'
    },
    /**
     * Name of the field in HTTP request of form submission.
     * @var string|null $name
     */
    'name'   => null,
    /**
     * Current value.
     * @var string|null $value
     */
    'value'  => null,
    /**
     * Field label.
     * @var string|array|LabelDriver|false|null $label
     */
    'label' => null,
    /**
     * Field label position.
     * @var string $label_position
     */
    'label_position' => 'before',
    /**
     * List of parameters of the template view|View instance.
     * @var array|ViewInterface $view
     */
    'view' => []
    
    /** 
     * Input field driver parameters
     * -------------------------------------------------------------------------- 
     */
    /**
     * HTML tag type attribute.
     * @var string|null $type 
     */
    'type'       => 'text',
]);

echo $input;
```

### Field driver instance methods

```php
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', function () {
    return '<label>Hello : </label><input name="username" placeholder="Please, enter your name">';
});

if ($hello = $field->get('hello')) {
    // Gets alias identifier.
    printf('alias: %s <br/>', $hello->getAlias());

    // Gets the base prefix of HTML class.
    printf('base HTML class: %s <br/>', $hello->getBaseClass());

    // Gets the unique identifier.
    printf('identifier: %s <br/>', $hello->getId());

    // Gets the index in related field manager.
    printf('index: %s <br/>', $hello->getIndex());
}
```

### View API usage

#### Plates view engine

Field driver used Plates as default template engine.

1. Creates a view file for the field driver.

```php
// /var/www/html/views/field/hello.plates.php file
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
echo '<label>Hello : </label><input name="username" placeholder="Please, enter your name">';
```

2. Creates and call a field driver with this above file directory as view directory.

```php
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', new class extends FieldDriver{});

echo $field->get('hello', ['view' => [
    /**
     * View directory absolute path (required).
     * @var string
     */
    'directory' => '/var/www/html/views/field/',
    /**
     * View override directory absolute path.
     * @var string|null
     */
    'override_dir' => null,
    /**
     * View render main template name. index is used by default if its null.
     * @var string|null
     */
    'template_name' => 'hello'
]]);
```

#### Uses another view engine

Your are free to used your own instance of Pollen\View\ViewInterface as the field driver view parameter if needed. In
this example Twig engine is used instead Plates.

1. Creates a view file for the field driver.

```html
<!-- /var/www/html/views/field/hello/index.html.twig file -->
<label>Hello : </label><input name="username" placeholder="Please, enter your name">
```

2. Creates and call a field driver with this above file directory as view directory.

```php
use Pollen\View\ViewManager;
use Pollen\Partial\FieldDriver;
use Pollen\Partial\FieldManager;

$field = new FieldManager();

$field->register('hello', new class extends FieldDriver{});

$viewEngine = (new ViewManager())->createView('twig')->setDirectory('/var/www/html/views/field/hello/');

echo $field->get('hello', ['view' => $viewEngine]);
```

### Routing API usage

In some cases, field driver should be able to send a response through a controller, for example to respond from a rest
api call.

Fortunately, all field driver instance are related to a route stack and have a reponseController method to do that. The
field driver route stack is created for all known HTTP methods (GET, POST, PATH, OPTIONS, DELETE) and for a particular
api method that works with XHR HTTP request.

1. Creates a field driver and gets its route url for the get http method.

```php
use Pollen\Http\Response;
use Pollen\Http\ResponseInterface;
use Pollen\Field\FieldDriver;
use Pollen\Field\FieldManager;

$field = new FieldManager();

$field->register('hello', new class extends FieldDriver {
    public function responseController(...$args) : ResponseInterface {
        return new Response('Hello World');
    }
});

// Gets the route url for the get HTTP method
echo $field->getRouteUrl('hello', null, [], 'get');
```

2. Now you can call the route url in your browser.

[Get the field response](/_field/hello/responseController)

Obviously, you are free to use your own routing stack and them controller methods instead.

## Field drivers

Natively, the Field component provides a collection of predefined drivers.  

### Button

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$button = $field->get('button', [
    /**
     * Text content of HTML button tag.
     * @var string $content
     */
    'content' => 'Send',
    /**
     * Type attribute in HTML tag.
     * @var string $type
     */
    'type'    => 'button'
]);

echo $button;
```

### Checkbox

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$checkbox = $field->get('checkbox', [
    /**
     * Theme style.
     * @var string base|toggle|none
     */
    'theme'   => 'base',
    /**
     * Selection value.
     * @var string
     */
    'checked' => 'on'
]);

echo $checkbox;
```

### Checkbox collection

```php
use Pollen\Field\Drivers\CheckboxDriver;
use Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceInterface;
use Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceCollectionInterface;

/** @var \Pollen\Field\FieldManagerInterface $field */
$checkboxCollection = $field->get('checkbox-collection', [
    /**
     * List of checkboxes choice.
     * @var array|CheckboxDriver[]|CheckboxChoiceInterface[]|CheckboxChoiceCollectionInterface $choices
     */
    'choices' => ['test1', 'test2' => ['test2-1', 'test2-2']],
]);

echo $checkboxCollection;
```

### File

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$file = $field->get('file', [
    /**
     * Allow selection of multiple files.
     * @var bool $multiple
     */
    'multiple' => false
]);

echo $file;
```

### Hidden

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$hidden = $field->get('hidden', [
    /**
     * Name of the field in HTTP request of form submission.
     * @var string|null $name
     */
    'name'   => 'name',
    /**
     * Value of the field in HTTP request of form submission.
     * @var string|null $value
     */
    'value'  => 'John Doe',
]);

echo $hidden;
```

### Input

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$input = $field->get('input', [
    /**
     * Type attribute in HTML tag.
     * @var string $type
     */
    'type'    => 'text'
]);

echo $input;
```

### Label

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$label = $field->get('label', [
    /**
     * Label HTML content.
     * @var string $content
     */
    'content' => ''
]);

echo $label;
```

### Number

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$number = $field->get('number');

echo $number;
```

### Password

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$password = $field->get('password');

echo $password;
```

### Radio

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$radio = $field->get('radio', [
    /**
     * Selection value.
     * @var string
     */
    'checked' => 'on'
]);

echo $radio;
```

### Radio collection

```php
use Pollen\Field\Drivers\RadioDriver;
use Pollen\Field\Drivers\RadioCollection\RadioChoiceInterface;
use Pollen\Field\Drivers\RadioCollection\RadioChoiceCollectionInterface;

/** @var \Pollen\Field\FieldManagerInterface $field */
$radioCollection = $field->get('radio-collection', [
    /**
     * List of radio buttons choice.
     * @var array|RadioDriver[]|RadioChoiceInterface[]|RadioChoiceCollectionInterface $choices
     */
    'choices' => ['test1', 'test2' => ['test2-1', 'test2-2']],
]);

echo $radioCollection;
```

### Required

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$required = $field->get('required', [
    /**
     * Required flag text.
     * @var string
     */
    'content'   => '*',
    /**
     * Label.
     * @var string|array|LabelDriver|false|null $label
     */
    'label' => null,
]);

echo $required;
```

### Select

```php
use Pollen\Field\Drivers\SelectDriver;
use Pollen\Field\Drivers\SelectCollection\SelectChoiceInterface;
use Pollen\Field\Drivers\SelectCollection\SelectChoiceCollectionInterface;

/** @var \Pollen\Field\FieldManagerInterface $field */
$select = $field->get('select', [
    /**
     * List of selection choices.
     * @var string[]|array|SelectChoiceInterface[]|SelectChoiceCollectionInterface $choices.
     */
    'choices'  => ['test1' => 'test1', 'test2' => ['test2-1', 'test2-2']],
    /**
     * Allow selection of multiple items.
     * @var bool $multiple
     */
    'multiple' => false,
    /**
     * Enable HTML wrapper.
     * @var bool
     */
    'wrapper' => false
]);

echo $select;
```

### Submit

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$submit = $field->get('submit', [
    /**
     * Button text and value in HTTP request form submission.
     * @var string $value
     */
    'value' => 'Send'
]);

echo $submit;
```

### Text

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$text = $field->get('text');

echo $text;
```

### Textarea

```php
/** @var \Pollen\Field\FieldManagerInterface $field */
$textarea = $field->get('textarea');

echo $textarea;
```