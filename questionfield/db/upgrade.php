<?php
function xmldb_local_questionfield_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2024061400) {
        $table = new xmldb_table('question');
        $field = new xmldb_field('question_number', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'name');

        if (!$DB->get_manager()->field_exists($table, $field)) {
            $DB->get_manager()->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2024061400, 'local', 'questionfield');
    }

    return true;
}
