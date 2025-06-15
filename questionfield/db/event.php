<?php
// local/questionfield/db/events.php

defined('MOODLE_INTERNAL') || die();

$handlers = array(
    'core_question_form_definition_after_data_load' => array(
        'handler' => 'local_questionfield_question_form_definition',
        'includefile' => '/local/questionfield/lib.php',
        'internal' => true,
    ),
    'core_question_before_save' => array(
        'handler' => 'local_questionfield_question_save_question',
        'includefile' => '/local/questionfield/lib.php',
        'internal' => true,
    ),
);