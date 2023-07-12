<?php
require_once('../../../config.php');
$courseids_string = required_param('courseids',PARAM_RAW);
$templateids_string = optional_param('templateids','',PARAM_RAW);
$userids_string = optional_param('userids','',PARAM_RAW);
global $DB,$CFG;
$where = array();
$params = array();
if($userids_string){
    $where[] = "userid in (?)";
    $params[] = implode(',',explode(',',$userids_string));
}
if($courseids_string){
    $where[] = "courseid in (?)";
    $params[] = implode(',',explode(',',$courseids_string));
}
if($templateids_string){
    $where[] = "templateid in (?)";
    $params[] = implode(',',explode(',',$templateids_string));
}

if(count($where)>0){
    $whereselect = implode(" and ",$where);
    $sql = "SELECT * from {tool_certificate_issues} where {$whereselect}";
    $issues = $DB->get_records_sql($sql,$params);
    if(count($issues)==0){
        redirect($CFG->wwwroot."/admin/tool/downloadcoursecertificate/downloadpage.php",get_string("no_results",'tool_downloadcoursecertificate'));
        die("");
    }
    $zip = new ZipArchive();
    $archive_file_name = 'certificates.zip';
    if ($zip->open($archive_file_name, ZipArchive::CREATE|ZipArchive::OVERWRITE)!==TRUE) {
        exit(get_string('error_zip','tool_downloadcoursecertificate'));
    }
    foreach($issues as $issue){
        $template = \tool_certificate\template::instance($issue->templateid);
        $file = $template->get_issue_file($issue);
        $zip->addFromString($file->get_filename(),$file->get_content());
        //$zip->addFile($template->get_issue_file_url($issue)->__toString());
    }
    $zip->close();
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=$archive_file_name");
    header("Content-length: " . filesize($archive_file_name));
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("$archive_file_name");
    die("");
}
else{
    redirect($CFG->wwwroot."/admin/tool/downloadcoursecertificate/downloadpage.php",get_string("no_results",'tool_downloadcoursecertificate'));
    die("");
}