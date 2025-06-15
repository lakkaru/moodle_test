<?php
// local/questionnumber/version.php

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_questionnumber'; // Full name of the plugin (used for diagnostics)
$plugin->version = 2025061205;              // YYYYMMDDXX (last two digits are for increments)
$plugin->requires = 2023100900;             // Moodle 4.3 (Moodle_50_STABLE refers to 2023100900)
$plugin->maturity = MATURITY_BETA;          // MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC, MATURITY_STABLE
$plugin->release = '5.0.1+ (Build 2025061200)';