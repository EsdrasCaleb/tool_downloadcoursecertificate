<?php
require_once('../../../config.php');
require_login();
global $CFG,$PAGE;
$PAGE->set_context(context_system::instance());
require_once('download_form.php');

//Instantiate simplehtml_form 
$mform = new download_form();
require_login();
require_capability('moodle/user:create', context_system::instance());

$PAGE->set_url($CFG->wwwroot."/admin/tool/downloadcoursecertificate/downloadpage.php");



//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot."/admin");
} else if ($fromform = $mform->get_data()) {
    $url = new moodle_url('/admin/tool/downloadcoursecertificate/certificates.php',
        array('courseids' => implode(',',$fromform->courseids)));
    redirect($url);
  
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
  echo $OUTPUT->header();
  //Set default data (if any)
  //$mform->set_data($toform);
  //displays the form
  echo $OUTPUT->box_start('generalbox');
  $mform->display();
  echo $OUTPUT->box_end();
  echo $OUTPUT->footer();

}

