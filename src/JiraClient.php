<?php

namespace HieuLe\LaravelJiraReporter;

use GuzzleHttp\Client;
use HieuLe\LaravelJiraReporter\Contracts\JiraClientContract;

class JiraClient implements JiraClientContract
{
    protected $jiraUrl;
    protected $jiraUsername;
    protected $jiraPassword;

    /**
     * @var Client
     */
    protected $client;

    function __construct(Client $client, $username, $password, $domain, $secured = true, $port = null)
    {
        $this->client       = $client;
        $this->jiraUsername = $username;
        $this->jiraPassword = $password;

        $scheme = $secured ? "https" : "http";
        if ($port) {
            $this->jiraUrl = "{$scheme}://{$domain}:{$port}/rest";
        } else {
            $this->jiraUrl = "{$scheme}://{$domain}/rest";
        }
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getJiraUrl()
    {
        return $this->jiraUrl;
    }

    /**
     * @return mixed
     */
    public function getJiraUsername()
    {
        return $this->jiraUsername;
    }

    /**
     * @return mixed
     */
    public function getJiraPassword()
    {
        return $this->jiraPassword;
    }

    /**
     * Create new issue
     *
     * @see https://docs.atlassian.com/jira/REST/server/#api/2/issue-createIssue
     *
     * @param $input
     *
     * @return array
     *
     * @throws JiraApiException
     */
    public function createIssue($input)
    {
        return $this->query('POST', 'api/2/issue', $input);
    }

    /**
     * Search for issues
     *
     * @param string $title the issue title
     *
     * @return array
     *
     * @throws JiraApiException
     */
    public function searchIssues($title)
    {
        return $this->query('GET', 'api/2/search', [
            'jql' => "summary~\"" . $title . "\"",
        ]);
    }

    /**
     * Add a comment to an issue
     *
     * @see https://docs.atlassian.com/jira/REST/server/#api/2/issue-addComment
     *
     * @param string $issueId
     * @param array $input
     *
     * @return array
     *
     * @throws JiraApiException
     */
    public function addComment($issueId, $input)
    {
        return $this->query('POST', "api/2/issue/{$issueId}/comment", $input);
    }

    /**
     * Send the request to JIRA server
     *
     * @param string $method   GET, POST, PUT, DELETE ...
     * @param string $endpoint the API endpoint
     * @param array  $data     the post data
     *
     * @return array the API results
     *
     */
    protected function query($method, $endpoint, $data)
    {
        // Build the URL
        $url = "{$this->jiraUrl}/{$endpoint}";

        // Basic Auth: [username, password]
        $auth = [
            $this->jiraUsername,
            $this->jiraPassword,
        ];

        // Send the request
        if ($method == 'GET') {
            $response = $this->client->get($url, [
                'query' => $data,
                'auth'  => $auth,
            ]);
        } else {
            $response = $this->client->post($url, [
                'json' => $data,
                'auth' => $auth,
            ]);
        }

        if ($response->getStatusCode() < 300) {
            // parse the JSON data and return the exception if the data is not valid
            $data = \json_decode((string)$response->getBody(), true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                $exception = new JiraApiException("JIRA server did not return a valid JSON");
                $exception->setUrl($url)
                    ->setStatusCode($response->getStatusCode())
                    ->setResponse((string)$response->getBody());

                throw $exception;
            }

            return $data;
        }

        // throw the exception if JIRA server returned errors
        $exception = new JiraApiException("JIRA server returned errors");
        $exception->setUrl($url)
            ->setStatusCode($response->getStatusCode())
            ->setResponse((string)$response->getBody());

        throw $exception;
    }
}