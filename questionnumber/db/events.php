<?php
// local/questionnumber/db/events.php

defined('MOODLE_INTERNAL') || die();

$handlers = array(
    'core_question_form_definition_after_data_load' => array(
        'handler' => 'local_questionnumber_question_form_definition',
        'includefile' => '/local/questionnumber/lib.php', // <-- This is the includefile
        'internal' => true,
    ),
    'core_question_before_save' => array(
        'handler' => 'local_questionnumber_question_save_question',
        'includefile' => '/local/questionnumber/lib.php', // <-- And this is the includefile
        'internal' => true,
    ),
);