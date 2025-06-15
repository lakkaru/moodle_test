<?php
namespace local_questionfield\form;

use core_form\dynamic_form;
use MoodleQuickForm;

defined('MOODLE_INTERNAL') || die();

class question_number_form_hook extends \core_question\form\question_form_hook {
    public function definition_after_data(\MoodleQuickForm $mform) {
        $mform->addElement('text', 'question_number', get_string('questionnumber', 'local_questionfield'));
        $mform->addHelpButton('question_number', 'questionnumber', 'local_questionfield');
        $mform->setType('question_number', PARAM_TEXT);
    }

    public function data_postprocessing(array $data): array {
        if (!isset($data['question_number'])) {
            $data['question_number'] = '';
        }
        return $data;
    }

    public function data_preprocessing(array $data): array {
        global $DB;
        if (!empty($data['id'])) {
            $record = $DB->get_record('question', ['id' => $data['id']], 'question_number', IGNORE_MISSING);
            if ($record && isset($record->question_number)) {
                $data['question_number'] = $record->question_number;
            }
        }
        return $data;
    }

    public function validation(array $data, array $files): array {
        $errors = [];
        // You can add validation rules here.
        return $errors;
    }

    public function question_saver(array $data): void {
        global $DB;
        if (!empty($data['id']) && isset($data['question_number'])) {
            $DB->set_field('question', 'question_number', $data['question_number'], ['id' => $data['id']]);
        }
    }
}
