<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2023 David Young
 * @license   https://github.com/aphiria/aphiria/blob/1.x/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Output\Compilers\Elements;

/**
 * Defines an output element
 */
final readonly class Element
{
    /**
     * @param string $name The name of the element
     * @param Style $style The style of the element
     */
    public function __construct(public string $name, public Style $style)
    {
    }
}
