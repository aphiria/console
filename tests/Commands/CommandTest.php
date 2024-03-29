<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2023 David Young
 * @license   https://github.com/aphiria/aphiria/blob/1.x/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Tests\Commands;

use Aphiria\Console\Commands\Command;
use Aphiria\Console\Input\Argument;
use Aphiria\Console\Input\ArgumentType;
use Aphiria\Console\Input\Option;
use Aphiria\Console\Input\OptionType;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testPropertiesAreSetInConstructor(): void
    {
        $expectedName = 'foo';
        $expectedArguments = [new Argument('arg', ArgumentType::Required, 'description')];
        $expectedOptions = [new Option('opt', OptionType::RequiredValue, 'o', 'description')];
        $expectedDescription = 'description';
        $expectedHelpText = 'help';
        $command = new Command(
            $expectedName,
            $expectedArguments,
            $expectedOptions,
            $expectedDescription,
            $expectedHelpText
        );
        $this->assertSame($expectedName, $command->name);
        $this->assertSame($expectedArguments, $command->arguments);
        $this->assertSame($expectedOptions, $command->options);
        $this->assertSame($expectedDescription, $command->description);
        $this->assertSame($expectedHelpText, $command->helpText);
    }
}
