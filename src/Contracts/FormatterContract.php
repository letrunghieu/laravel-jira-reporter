<?php

namespace HieuLe\LaravelJiraReporter\Contracts;

interface FormatterContract
{
    public function formatDescription(\Exception $e, array $extra);

    public function formatComment(\Exception $e, array $extra);

    public function formatMainTitle(\Exception $e, array $extra, $withCountNumber = true);

    public function formatSubtaskTitle(\Exception $e, array $extra);

    public function updateCount($string);
}