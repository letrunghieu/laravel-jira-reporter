<?php

namespace HieuLe\LaravelJiraReporter;

use HieuLe\LaravelJiraReporter\Contracts\FormatterContract;
use HieuLe\LaravelJiraReporter\Contracts\JiraClientContract;

class Reporter
{
    /**
     * @var JiraClientContract
     */
    protected $jiraClient;

    /**
     * @var FormatterContract
     */
    protected $formatter;

    function __construct()
    {

    }

    /**
     * @return JiraClientContract
     */
    public function getJiraClient()
    {
        return $this->jiraClient;
    }

    /**
     * @param JiraClientContract $jiraClient
     *
     * @return Reporter
     */
    public function setJiraClient($jiraClient)
    {
        $this->jiraClient = $jiraClient;

        return $this;
    }

    /**
     * @return FormatterContract
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param FormatterContract $formatter
     *
     * @return Reporter
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function report(\Exception $e, array $extra)
    {

        $mainTitle = $this->formatter->formatMainTitle($e, $extra);
        $response  = $this->jiraClient->searchIssue($mainTitle);

        if ($response) {
            // no issue found with the same bug
        } else {
            // found an issue with the same bug

            // update issue count

            // check if the bug has subtasks
            if (true) {
                // no subtask found
            } else {
                // subtask found
            }
        }
    }
}