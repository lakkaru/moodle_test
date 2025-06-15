<?php
// local/questionfield/lib.php

defined('MOODLE_INTERNAL') || die();

/**
 * Hook into the question editing form definition.
 * This function will be called when any question type form is being built.
 *
 * @param MoodleQuickForm $mform The Moodle form instance.
 * @param stdClass        $question The question object.
 */
function local_questionfield_question_form_definition($mform, $question) {
    // Add a text input field for question_number
    $mform->addElement('text', 'question_number', get_string('questionfield', 'local_questionfield'), array('size' => '20'));
    $mform->setType('question_number', PARAM_TEXT); // Ensure it's text, or PARAM_INT if you want numbers only
    $mform->setDefault('question_number', @$question->question_number); // Set default value from existing question

    // Add help button if needed
    $mform->addHelpButton('question_number', 'questionfield', 'local_questionfield');

    // Make it optional or required
    // $mform->addRule('question_number', get_string('required'), 'required', null, 'client');
}

/**
 * Hook into the question saving process.
 * This function will be called when any question is being saved.
 *
 * @param stdClass $question The question object to be saved.
 * @param array $formdata The submitted form data.
 */
function local_questionfield_question_save_question($question, $formdata) {
    // Ensure the new field is set on the question object before saving to DB
    if (isset($formdata->question_number)) {
        $question->question_number = $formdata->question_number;
    } else {
        $question->question_number = ''; // Or set a default/null if not provided
    }
}