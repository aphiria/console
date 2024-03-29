<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2023 David Young
 * @license   https://github.com/aphiria/aphiria/blob/1.x/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Output\Lexers;

/**
 * Defines the different output token types
 */
enum OutputTokenType
{
    /** Defines an end of file token type */
    case Eof;
    /** Defines a close tag token type */
    case TagClose;
    /** Defines an open tag token type */
    case TagOpen;
    /** Defines a word token type */
    case Word;
}
