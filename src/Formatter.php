<?php

namespace HieuLe\LaravelJiraReporter;

use HieuLe\LaravelJiraReporter\Contracts\FormatterContract;

class Formatter implements FormatterContract
{

    public function formatComment(\Exception $e, array $extra)
    {
        $extraStr      = json_encode($extra, JSON_PRETTY_PRINT);
        $stackTraceStr = $e->getTraceAsString();

        $comment = <<<COMMENT
Stack trace:
{noformat}
{$stackTraceStr}
{noformat}

Extra information
{noformat}
{$extraStr}
{noformat}
COMMENT;

        return $comment;
    }

    /**
     * Format the issue title
     * <prefix> - <Error message> - <File name> line <line> [1]
     *
     * @param \Exception $e
     * @param array      $extra
     *
     * @return string
     */
    public function formatMainTitle(\Exception $e, array $extra)
    {
        $prefix        = array_get($extra, 'prefix');
        $titleElements = [];
        if ($prefix) {
            $titleElements[] = "$prefix -";
        }

        $titleElements[] = $e->getMessage();
        $titleElements[] = "- " . basename($e->getFile()) . " line " . $e->getLine();
        $titleElements[] = "[1]";

        return implode(" ", $titleElements);
    }

    /**
     * Format the subtask title
     * <env name> [1]
     *
     * @param \Exception $e
     * @param array      $extra
     *
     * @return string
     */
    public function formatSubtaskTitle(\Exception $e, array $extra)
    {
        $environmentName = array_get($extra, 'env', "application");

        return "{$environmentName} [1]";
    }

    /**
     * Format the issue description
     *
     * @param \Exception $e
     * @param array      $extra
     *
     * @return string
     */
    public function formatDescription(\Exception $e, array $extra)
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Update the count number
     *
     * @param string $string
     *
     * @return string
     */
    public function updateCount($string)
    {
        $re = '/\[(\d+)\]$/mi';

        $newString = preg_replace_callback($re, function ($matches) {
            return "[" . ($matches[1] + 1) . "]";
        }, $string);

        return $newString;
    }
}