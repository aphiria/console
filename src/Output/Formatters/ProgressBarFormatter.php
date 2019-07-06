<?php

/**
 * Aphiria
 *
 * @link      https://www.aphiria.com
 * @copyright Copyright (C) 2019 David Young
 * @license   https://github.com/aphiria/console/blob/master/LICENSE.md
 */

declare(strict_types=1);

namespace Aphiria\Console\Output\Formatters;

use Aphiria\Console\Output\IOutput;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;

/**
 * Defines the formatter for progress bars
 */
final class ProgressBarFormatter
{
    /** @const The width of the screen to fill */
    private const PROGRESS_BAR_WIDTH = 80;
    /** @var string The progress character */
    public string $progressChar = '=';
    /** @var string The remaining progress character */
    public string $remainingProgressChar = '-';
    /** @var IOutput The output to draw to */
    private IOutput $output;
    /** @var DateTimeImmutable The start time of the progress bar */
    private DateTimeImmutable $startTime;
    /** @var string The output string format */
    private ?string $outputFormat;
    /** @var int The frequency in seconds we redraw the progress bar */
    private int $redrawFrequency = 1;
    /** @var bool Whether or not this is the first time we've output the progress bar */
    private bool $isFirstOutput = true;

    /**
     * @param IOutput $output The output to draw to
     * @param string|null $outputFormat The output format to use, or null if using the default
     *      Acceptable placeholders are 'progress', 'maxSteps', 'bar', and 'timeRemaining'
     * @throws InvalidArgumentException Thrown if the max steps are invalid
     * @throws Exception Thrown if there was an unhandled exception creating the start time
     */
    public function __construct(IOutput$output, string $outputFormat = null)
    {
        $this->output = $output;
        $this->outputFormat = $outputFormat ?? '%bar% %progress%/%maxSteps%' . PHP_EOL . 'Time remaining: %timeRemaining%';
        $this->startTime = new DateTimeImmutable();
    }

    /**
     * Handles an update to the progress
     *
     * @param int $prevProgress The previous progress
     * @param int $currProgress The current progress
     * @param int $maxSteps The max number of steps that can be taken
     * @throws Exception Thrown if there was an error drawing the progress bar
     */
    public function onProgress(int $prevProgress, int $currProgress, int $maxSteps): void
    {
        // Only redraw if we've completed the progress or if it has been at least the redraw frequency since the last progress
        $shouldRedraw = $prevProgress === $maxSteps
            || floor($prevProgress / $this->redrawFrequency) !== floor($currProgress / $this->redrawFrequency);

        if ($shouldRedraw) {
            $this->output->write($this->formatOutput($currProgress, $maxSteps));
        }

        // Give ourselves a new line if the progress bar is finished
        if ($currProgress === $maxSteps) {
            $this->output->writeln('');
        }
    }

    /**
     * Formats the output for display
     *
     * @param int $progress The current progress
     * @param int $maxSteps The max steps that can be taken
     * @return string The formatted output string
     * @throws Exception Thrown if there was an error formatting the output
     */
    private function formatOutput(int $progress, int $maxSteps): string
    {
        if ($progress === $maxSteps) {
            // Don't show the percentage anymore
            $progressCompleteString = str_repeat($this->progressChar, self::PROGRESS_BAR_WIDTH - 2);
            $progressLeftString = '';
        } else {
            $percentComplete = floor(100 * $progress / $maxSteps);
            $paddedBarProgress = str_pad("$percentComplete%%", 3, $this->remainingProgressChar);
            $progressCompleteString = str_repeat(
                    $this->progressChar,
                    max(0, floor($progress / $maxSteps * (self::PROGRESS_BAR_WIDTH - 2) - strlen($paddedBarProgress)))
                ) . $paddedBarProgress;
            $progressLeftString = str_repeat(
                $this->remainingProgressChar,
                self::PROGRESS_BAR_WIDTH - 1 - strlen($progressCompleteString)
            );
        }

        $compiledOutput = str_replace(
            ['%progress%', '%maxSteps%', '%bar%', '%timeRemaining%'],
            [
                $progress,
                $maxSteps,
                '[' . $progressCompleteString . $progressLeftString . ']',
                $this->getEstimatedTimeRemaining($progress, $maxSteps)
            ],
            $this->outputFormat
        );

        if ($this->isFirstOutput) {
            $this->isFirstOutput = false;

            // Still use sprintf() because there's some formatted strings in the output
            return sprintf($compiledOutput, '');
        }

        // Clear previous output
        $newLineCount = substr_count($this->outputFormat, PHP_EOL);

        return sprintf("\033[2K\033[0G\033[{$newLineCount}A\033[2K$compiledOutput", '', '');
    }

    /**
     * Gets the estimated time remaining
     *
     * @param int $progress The current progress
     * @param int $maxSteps The max steps that can be taken
     * @return string The estimated time remaining
     */
    private function getEstimatedTimeRemaining(int $progress, int $maxSteps): string
    {
        if ($progress === 0) {
            // We cannot estimate the time remaining if no progress has been made
            return 'Estimating...';
        }

        if ($progress === $maxSteps) {
            return 'Complete';
        }

        $elapsedTime = time() - $this->startTime->getTimestamp();
        $secondsRemaining = round($elapsedTime * $maxSteps / $progress - $elapsedTime);
        $timeFormats = [
            [0, 'less than 1 sec'],
            [1, '1 sec'],
            [2, 'secs', 1],
            [60, '1 min'],
            [120, 'mins', 60],
            [3600, '1 hr'],
            [7200, 'hrs', 3600],
            [86400, '1 day'],
            [172800, 'days', 86400],
        ];

        foreach ($timeFormats as $index => $timeFormat) {
            if ($secondsRemaining >= $timeFormat[0]) {
                if ((isset($timeFormats[$index + 1]) && $secondsRemaining < $timeFormats[$index + 1][0])
                    || count($timeFormats) === $index - 1
                ) {
                    if (count($timeFormat) === 2) {
                        return $timeFormat[1];
                    }

                    return floor($secondsRemaining / $timeFormat[2]) . ' ' . $timeFormat[1];
                }
            }
        }

        return 'Estimating...';
    }
}
