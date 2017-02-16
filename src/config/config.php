<?php
return [
    'default'     => 'default',

    /*
     * JIRA server configuration
     */
    'connections' => [
        'default' => [

            /*
             * JIRA server domain name
             */
            'host'     => '',

            /*
             * JIRA server port
             */
            'port'     => '',

            /*
             * The project key to create issues
             */
            'project'  => '',

            /*
             * JIRA username
             */
            'username' => '',

            /*
             * JIRA password
             */
            'password' => '',

            /*
             * Use HTTPS instead of HTTP protocol
             */
            'secured'  => true,
        ],
    ],
];