<?php

declare(strict_types=1);

namespace Pollen\Field;

use Pollen\ViewExtends\PlatesTemplateInterface;

/**
 * @method string after()
 * @method string before()
 * @method string getAlias()
 * @method string getId()
 * @method string getIndex()
 * @method string getName()
 * @method string getValue()
 * @method string label(string $position) Use before|after for position. before by default.
 */
interface FieldTemplateInterface extends PlatesTemplateInterface
{
}