<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information
 *
 * @package    tool
 * @subpackage downloadcoursecertificate
 * @copyright  2023 Esdras Caleb
 * @author     Esdras Caleb <esdrascaleb@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin version.
$plugin->version = 2023061705;

// Required Moodle version.
$plugin->requires  = 2013050100; // Requires this Moodle version - at least 2.0

// Full name of the plugin.
$plugin->component = 'tool_downloadcoursecertificate';

// Software maturity level.
$plugin->maturity = MATURITY_BETA;

// User-friendly version number.
$plugin->release = '0.0.0.1';

$plugin->dependencies = [
    'mod_coursecertificate' => ANY_VERSION,
];