<?php

declare(strict_types = 1);

namespace Supermetrics\PHPStan\Command\ErrorFormatter;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\Command\Output;

class MarkdownErrorFormatter implements ErrorFormatter
{
    /**
     * Formats the errors and outputs them to the console.
     *
     * @param AnalysisResult $analysisResult
     * @param Output         $output
     *
     * @return int Error code.
     */
    public function formatErrors(AnalysisResult $analysisResult, Output $output): int
    {
        $output->writeLineFormatted('');

        $nonFileSpecificErrors = $analysisResult->getNotFileSpecificErrors();

        if (!empty($nonFileSpecificErrors)) {
            $output->writeRaw('## Non file specific errors');

            foreach ($nonFileSpecificErrors as $notFileSpecificError) {
                $output->writeLineFormatted(sprintf('- %s', $notFileSpecificError));
            }
        }

        $fileSpecificErrors = $analysisResult->getFileSpecificErrors();

        if (!empty($fileSpecificErrors)) {
            $output->writeRaw('## Errors');
            $output->writeLineFormatted('');

            $fileErrors = [];

            foreach ($analysisResult->getFileSpecificErrors() as $errorMessage) {
                if (!$errorMessage->canBeIgnored()) {
                    continue;
                }

                $fileErrors[$errorMessage->getFilePath()][] = $errorMessage;
            }

            foreach ($fileErrors as $file => $errorMessages) {
                $output->writeLineFormatted('');
                $output->writeRaw(sprintf('#### __%s__', $file));
                $output->writeLineFormatted('');

                $output->writeRaw('Line | Error');
                $output->writeLineFormatted('');
                $output->writeLineFormatted('---- | ------');

                foreach ($errorMessages as $errorMessage) {
                    $output->writeRaw(
                        sprintf(
                            '%s | %s',
                            $errorMessage->getLine() ?? '?',
                            $errorMessage->getMessage()
                        )
                    );
                    $output->writeLineFormatted('');
                }

                $output->writeLineFormatted('---');
            }
        }

        $warnings = $analysisResult->getWarnings();

        if (!empty($warnings)) {
            $output->writeRaw('## Warnings');
            $output->writeLineFormatted('');

            foreach ($warnings as $warning) {
                $output->writeRaw(sprintf('- %s', $warning));
                $output->writeLineFormatted('');
            }
        }

        return $analysisResult->hasErrors() ? 1 : 0;
    }
}
