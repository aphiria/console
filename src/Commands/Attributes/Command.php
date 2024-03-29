<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2023 David Young
 * @license   https://github.com/aphiria/aphiria/blob/1.x/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Commands\Attributes;

use Attribute;

/**
 * Defines the command attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Command
{
    /**
     * @param string $name The name of the command
     * @param string|null $description A brief description of the command, or null if there is none
     * @param string|null $helpText The extra descriptive help text, or null if there is none
     */
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $helpText = null
    ) {
    }
}
