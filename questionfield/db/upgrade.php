<?php
defined('MOODLE_INTERNAL') || die();
function xmldb_local_questionfield_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager(); // Get the DB manager for DDL operations.

    if ($oldversion < 2024061400) {
         // Define field question_number to be added to mdl_question
        $table = new xmldb_table('question');
         $field = new xmldb_field('question_number', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0', 'generalfeedback');

       // Conditionally add field question_number to mdl_question table
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // local_questionnumber savepoint reached
        upgrade_plugin_savepoint(true, 2024061400, 'local', 'questionfield');
    }

    return true;
}
