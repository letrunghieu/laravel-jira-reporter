<?php

namespace HieuLe\LaravelJiraReporter\Contracts;

interface JiraClientContract
{
    public function query($method, $endpoint, $data);
}