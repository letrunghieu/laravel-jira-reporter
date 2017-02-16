<?php

namespace HieuLe\LaravelJiraReporter;

use GuzzleHttp\Client;
use HieuLe\LaravelJiraReporter\Contracts\FormatterContract;
use HieuLe\LaravelJiraReporter\Contracts\JiraClientContract;

abstract class AbstractReporter
{
    /**
     * @var JiraClientContract
     */
    protected $jiraClient;

    /**
     * @var FormatterContract
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $configs;

    /**
     * AbstractReporter constructor.
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs   = $configs;
        $this->formatter = new Formatter();

        $httpClient   = new Client();
        $jiraHost     = $configs['host'];
        $jiraPort     = $configs['port'];
        $jiraUsername = $configs['username'];
        $jiraPassword = $configs['password'];
        $jiraSecured  = $configs['secured'];

        $this->jiraClient = new JiraClient(
            $httpClient,
            $jiraUsername,
            $jiraPassword,
            $jiraHost,
            $jiraSecured,
            $jiraPort);
    }

    public abstract function report(\Exception $e);
}