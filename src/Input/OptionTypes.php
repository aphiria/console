<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2020 David Young
 * @license   https://github.com/aphiria/aphiria/blob/master/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Input;

/**
 * Defines the different types of options
 */
final class OptionTypes
{
    /** The argument is required */
    public const REQUIRED_VALUE = 1;
    /** The argument is optional */
    public const OPTIONAL_VALUE = 2;
    /** The argument is not allowed */
    public const NO_VALUE = 4;
    /** The argument is an array */
    public const IS_ARRAY = 8;
}
