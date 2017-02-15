<?php

namespace HieuLe\LaravelJiraReporter\Contracts;

interface JiraClientContract
{
    public function createIssue($input);

    public function addComment($issueId, $input);

    public function searchIssues($title);
}