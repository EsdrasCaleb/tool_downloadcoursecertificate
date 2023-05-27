<?php
require_once('../../../config.php');
global $CFG;
require_once('download_form.php');

//Instantiate simplehtml_form 
$mform = new download_form();
require_login();
require_capability('moodle/user:create', context_system::instance());
$PAGE->set_context(context_system::instance());
$PAGE->set_url($CFG->wwwroot."/admin/tool/downloadcoursecertificate/downloadpage.php");



//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot."/admin");
} else if ($fromform = $mform->get_data()) {
  global $DB;
  $where = array();
  $params = array();
  if(count($fromform->userids)>0){
    $where[] = "userid in (?)";
    $params[] = implode(',',$fromform->userids);
  }
  if(count($fromform->courseids)>0){
    $where[] = "couseid in (?)";
    $params[] = implode(',',$fromform->courseids);
  }
  if(count($fromform->templateids)>0){
    $where[] = "templateid in (?)";
    $params[] = implode(',',$fromform->templateids);
  }
  if(count($where)>0){
    $whereselect = implode(" and ",$where);
    $sql = "SELECT * from {tool_certificate_issues} where {$whereselect}";
    $issues = $DB->get_records_sql($sql,$params);

    $zip = new ZipArchive();
    $archive_file_name = 'certificates.zip';
    if ($zip->open($archive_file_name, ZipArchive::CREATE)!==TRUE) {
      exit(get_string('error_zip','tool_downloadcoursecertificate'));
    }
    foreach($issues as $issue){
      $template = \tool_certificate\template::instance($issue->templateid);
      $file = $template->get_issue_file($issue);
      $zip->addFromString($file->get_filename(),$file->get_source());
    }
    header("Content-type: application/zip"); 
    header("Content-Disposition: attachment; filename=$archive_file_name");
    header("Content-length: " . filesize($archive_file_name));
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile("$archive_file_name");
  }

  
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
  echo $OUTPUT->header();
  //Set default data (if any)
  $mform->set_data($toform);
  //displays the form
  echo $OUTPUT->box_start('generalbox');
  $mform->display();
  echo $OUTPUT->box_end();
  echo $OUTPUT->footer();

}

