<?php
require_once("$CFG->libdir/formslib.php");

class download_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB;
       
        $mform = $this->_form; 

        $mform->addElement('select', 'type',get_string('type','tool_downloadcoursecertificate'),
            array(
            ''=>get_string('select'),
            'auto_user'=>get_string('users','tool_downloadcoursecertificate'), 
            'auto_course'=>get_string('courses','tool_downloadcoursecertificate'),
            'auto_template'=>get_string('templates','tool_downloadcoursecertificate')),
            array("onchange"=>"jQuery('.autodown').addClass('hidden');jQuery('.'+jQuery('#typeselect').val()).removeClass('hidden');",    
            'id'=>'typeselect')); 

        $mform->addRule('type', get_string('select_type','tool_downloadcoursecertificate'),
         'required',null,'client');
        $users = $DB->get_records_sql("SELECT id,firstname,lastname from {user} where deleted=0 and 
                                               id in (SELECT userid from {tool_certificate_issues})");

        $usernames = array();                                                                                                       
        foreach ($users as $user) {    

            $usernames[$user->id] = $user->firstname." ".$user->lastname;                                                                  
        }                                                                                                                           
        $options = array(                                                                                                           
            'multiple' => true,                                                  
            'noselectionstring' => get_string('search','tool_downloadcoursecertificate'),  
            'class'=>'autodown auto_user hidden' ,                                          
        );       

        $mform->addElement('autocomplete', 'userids', get_string('add_users','tool_downloadcoursecertificate'), $usernames, $options);


        $courses = $DB->get_records_sql("SELECT id,fullname from {course} where deleted=0 and 
                                       id in (SELECT courseid from {tool_certificate_issues})");

        $coursesnames = array();                                                                                                       
        foreach ($courses as $course) {      

            $coursesnames[$course->id] = $course->fullname;                                                                  
        }                                                                                                                           
        $options = array(                                                                                                           
            'multiple' => true,                                                  
            'noselectionstring' => get_string('search','tool_downloadcoursecertificate'),  
            'class'=>'autodown auto_course hidden' ,                                           
        );       

        $mform->addElement('autocomplete', 'courseids', get_string('add_courses','tool_downloadcoursecertificate'), $coursesnames, $options);

        $templates = $DB->get_records("tool_certificate_templates");
        $templatenames = array();                                                                                                       
        foreach ($templates as $template) {                                                                          
            $templatenames[$template->id] = $template->name;                                                                  
        }                                                                                                                           
        $options = array(                                                                                                           
            'multiple' => true,                                                  
            'noselectionstring' => get_string('search','tool_downloadcoursecertificate'),  
            'class'=>'autodown auto_template hidden' ,                                        
        );       

        $mform->addElement('autocomplete', 'templateids', get_string('add_templates','tool_downloadcoursecertificate'), $templatenames, $options);

        $this->add_action_buttons();
    }
}