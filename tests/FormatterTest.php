<?php

namespace HieuLe\LaravelJiraReporterTest;

use HieuLe\LaravelJiraReporter\LaravelJiraReportingServiceProvider;
use Orchestra\Testbench\TestCase;

class FormatterTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelJiraReportingServiceProvider::class,
        ];
    }

    function testFormatComment()
    {
        try {
            $do = 1 / 0;
        } catch (\Exception $e) {
            $result = app('jira_reporter.formatter')->formatComment($e, [
                'more' => [
                    'key'    => 'value',
                    'object' => [
                        'k' => 'v',
                    ],
                ],
            ]);

            $this->assertStringStartsWith('Stack trace', $result);
            $this->assertStringEndsWith('{noformat}', $result);
        }
    }

    function testFormatMainTitleWithPrefix()
    {
        try {
            $do = 1 / 0;
        } catch (\Exception $e) {
            $result = app('jira_reporter.formatter')->formatMainTitle($e, [
                'prefix' => 'JIRA',
                'more'   => [
                    'key'    => 'value',
                    'object' => [
                        'k' => 'v',
                    ],
                ],
            ]);

            $this->assertSame('JIRA - Division by zero - FormatterTest.php line 39 [1]', $result);
        }
    }

    function testFormatMainTitleWithoutPrefix()
    {
        try {
            $do = 1 / 0;
        } catch (\Exception $e) {
            $result = app('jira_reporter.formatter')->formatMainTitle($e, [
                'more' => [
                    'key'    => 'value',
                    'object' => [
                        'k' => 'v',
                    ],
                ],
            ]);

            $this->assertSame('Division by zero - FormatterTest.php line 58 [1]', $result);
        }
    }

    function testFormatSubtaskTitle()
    {
        $result = app('jira_reporter.formatter')->formatSubtaskTitle(new \Exception(), [
            'env' => 'testing',
        ]);

        $this->assertSame('testing [1]', $result);
    }

    function testFormatDescription()
    {
        $desc = app('jira_reporter.formatter')->formatDescription(new \Exception(), []);

        // 2017-02-15 08:38:21 (19 characters)
        $this->assertSame(19, strlen($desc));
    }

    function testUpdateCount()
    {
        $result = app('jira_reporter.formatter')->updateCount("Command text was not set for the command [1] object.:SearchReSale.asp:2245 [21]");

        $this->assertSame('Command text was not set for the command [1] object.:SearchReSale.asp:2245 [22]', $result);
    }
}