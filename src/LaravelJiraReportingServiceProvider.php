<?php

namespace HieuLe\LaravelJiraReporter;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class LaravelJiraReportingServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('jira_reporter.php'),
        ]);
    }

    public function register()
    {
        $this->registerFormatter();
        $this->registerJiraClient();
    }

    protected function registerFormatter()
    {
        $this->app->singleton('jira_reporter.formatter', function ($app) {
            return new Formatter();
        });
    }

    protected function registerJiraClient()
    {
        $this->app->singleton('jira_reporter.client', function ($app) {
            $client       = new Client();
            $jiraHost     = config('jira_reporter.jira.host');
            $jiraPort     = config('jira_reporter.jira.port');
            $jiraUsername = config('jira_reporter.jira.username');
            $jiraPassword = config('jira_reporter.jira.password');
            $jiraSecured  = config('jira_reporter.jira.secured');
            $jiraClient   = new JiraClient($client, $jiraUsername, $jiraPassword, $jiraHost, $jiraSecured, $jiraPort);

            return $jiraClient;
        });
    }
}