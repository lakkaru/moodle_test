<?php
// File: local/questionbank_api/db/services.php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_questionbank_api_test_connection' => array(
        'classname'   => 'local_questionbank_api_external',
        'methodname'  => 'test_connection',
        'classpath'   => 'local/questionbank_api/externallib.php',
        'description' => 'Test API connection',
        'type'        => 'read',
        'capabilities'=> '',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'local_questionbank_api_get_course_info' => array(
        'classname'   => 'local_questionbank_api_external',
        'methodname'  => 'get_course_info',
        'classpath'   => 'local/questionbank_api/externallib.php',
        'description' => 'Get course information',
        'type'        => 'read',
        'capabilities'=> '',
        'ajax'        => true,
        'loginrequired' => true
    ),
    'local_questionbank_api_get_simple_questions' => array(
        'classname'   => 'local_questionbank_api_external',
        'methodname'  => 'get_simple_questions',
        'classpath'   => 'local/questionbank_api/externallib.php',
        'description' => 'Get simple questions list',
        'type'        => 'read',
        'capabilities'=> 'moodle/question:viewall',
        'ajax'        => true,
        'loginrequired' => true
    )
);

$services = array(
    'Question Bank API' => array(
        'functions' => array(
            'local_questionbank_api_test_connection',
            'local_questionbank_api_get_course_info',
            'local_questionbank_api_get_simple_questions'
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'questionbank_api',
        'downloadfiles' => 0,
        'uploadfiles' => 0
    )
);