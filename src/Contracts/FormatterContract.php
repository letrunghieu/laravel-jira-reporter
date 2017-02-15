<?php

namespace HieuLe\LaravelJiraReporter\Contracts;

interface FormatterContract
{
    public function format($input);

    public function formatMainTitle(\Exception $e, array $extra);

    public function formatSubtaskTitle(\Exception $e, array $extra);
}