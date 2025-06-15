<?php
// File: local/questionbank_api/externallib.php
// Simple version for testing

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class local_questionbank_api_external extends external_api {

    /**
     * Simple test function to verify plugin is working
     */
    public static function test_connection_parameters() {
        return new external_function_parameters(array());
    }

    public static function test_connection() {
        global $USER, $DB;
        
        return array(
            'success' => true,
            'message' => 'Plugin is working correctly',
            'userid' => $USER->id,
            'username' => $USER->username,
            'timestamp' => time()
        );
    }

    public static function test_connection_returns() {
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'Success status'),
                'message' => new external_value(PARAM_RAW, 'Message'),
                'userid' => new external_value(PARAM_INT, 'User ID'),
                'username' => new external_value(PARAM_RAW, 'Username'),
                'timestamp' => new external_value(PARAM_INT, 'Timestamp')
            )
        );
    }

    /**
     * Get basic course info
     */
    public static function get_course_info_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'Course ID')
            )
        );
    }

    public static function get_course_info($courseid) {
        global $DB, $USER;

        $params = self::validate_parameters(self::get_course_info_parameters(),
            array('courseid' => $courseid));

        // Check if course exists
        $course = $DB->get_record('course', array('id' => $params['courseid']));
        if (!$course) {
            return array(
                'error' => true,
                'message' => 'Course not found with ID: ' . $params['courseid']
            );
        }

        // Get context
        try {
            $context = context_course::instance($params['courseid']);
            self::validate_context($context);
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Context error: ' . $e->getMessage()
            );
        }

        return array(
            'error' => false,
            'courseid' => $params['courseid'],
            'coursename' => $course->fullname,
            'contextid' => $context->id,
            'message' => 'Course found successfully'
        );
    }

    public static function get_course_info_returns() {
        return new external_single_structure(
            array(
                'error' => new external_value(PARAM_BOOL, 'Error status'),
                'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_OPTIONAL),
                'coursename' => new external_value(PARAM_RAW, 'Course name', VALUE_OPTIONAL),
                'contextid' => new external_value(PARAM_INT, 'Context ID', VALUE_OPTIONAL),
                'message' => new external_value(PARAM_RAW, 'Message')
            )
        );
    }

    /**
     * Simple questions retrieval
     */
    public static function get_simple_questions_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'Course ID')
            )
        );
    }

  public static function get_simple_questions($courseid) {
    global $DB, $USER;

    $params = self::validate_parameters(self::get_simple_questions_parameters(),
        array('courseid' => $courseid));

    try {
        // Check course exists
        if (!$DB->record_exists('course', array('id' => $params['courseid']))) {
            return array(
                'error' => true,
                'message' => 'Course does not exist',
                'questions' => array()
            );
        }

        // Get context
        $context = context_course::instance($params['courseid']);
        self::validate_context($context);

        // Check capability
        if (!has_capability('moodle/question:viewall', $context)) {
            return array(
                'error' => true,
                'message' => 'No permission to view questions',
                'questions' => array()
            );
        }

        // Corrected SQL query to use mdl_Youtubes and parameterize with course ID
        $sql = " SELECT
                    q.id AS question_id,
                    q.stamp AS question_stamp,
                    q.name AS question_name,
                    q.questiontext AS question_text,
                    q.qtype AS question_type,
                    qa.id AS answer_id,
                    qa.answer AS answer_text,
                    qa.fraction AS answer_fraction,
                    qa.feedback AS answer_feedback -- Including feedback as it's relevant for answers
                FROM
                    mdl_question q
                LEFT JOIN
                    mdl_question_answers qa ON q.id = qa.question
                ORDER BY
                    q.id, qa.fraction DESC, qa.id; -- Order by question ID, then correct answers first, then answer ID";

        // Pass the course ID from $params to the SQL query
        $records = $DB->get_records_sql($sql, array('courseid' => $params['courseid']));

        $questions = array();
        foreach ($records as $record) {
            $questionid = (int)$record->question_id;

            // Initialize question data if not already present
            if (!isset($questions[$questionid])) {
                $questions[$questionid] = array(
                    'id' => $questionid,
                    'name' => $record->question_name,
                    'questiontext' => strip_tags($record->question_text),
                    'qtype' => $record->question_type,
                    // 'course_name' => $record->course_name,
                    'answers' => array(), // Initialize answers array
                    'tags' => array()      // Initialize tags array
                );
            }

            // Add answers if they exist for this question
            if (!empty($record->answer_id)) {
                $questions[$questionid]['answers'][$record->answer_id] = array(
                    'id' => (int)$record->answer_id,
                    'text' => $record->answer_text,
                    'fraction' => (float)$record->answer_fraction
                );
            }

            // // Add tags if they exist for this question
            // if (!empty($record->tag_name)) {
            //     $questions[$questionid]['tags'][] = $record->tag_name;
            // }
        }

        // Re-index the array from associative to sequential if preferred,
        // or keep as associative by question ID if that's more useful.
        $final_questions = array_values($questions);

        return array(
            'error' => false,
            'message' => 'Success - found ' . count($final_questions) . ' questions',
            'questions' => $final_questions
        );

    } catch (Exception $e) {
        return array(
            'error' => true,
            'message' => 'Exception: ' . $e->getMessage(),
            'questions' => array()
        );
    }
}

    public static function get_simple_questions_returns() {
        return new external_single_structure(
            array(
                'error' => new external_value(PARAM_BOOL, 'Error status'),
                'message' => new external_value(PARAM_RAW, 'Message'),
                'questions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Question ID'),
                            'name' => new external_value(PARAM_RAW, 'Question name'),
                            'questiontext' => new external_value(PARAM_RAW, 'Question text'),
                            'qtype' => new external_value(PARAM_ALPHA, 'Question type'),
                            // 'categoryname' => new external_value(PARAM_RAW, 'Category name')
                        )
                    )
                )
            )
        );
    }
}