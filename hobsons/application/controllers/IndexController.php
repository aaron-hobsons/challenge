<?php
class IndexController extends Zend_Controller_Action
{
	public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	/*
    	 * Instatiating these objects isn't really necessary, but doing it this way seemed more in line with the spirt of the challenge.
    	 */ 
    	
        $teacher=new Model_Teacher();
    	$teacher->setTitle('Mr.');
    	$teacher->setName('Robert Smith');
    	$teacher->setBirthDate('03-04-1970');
    	$teacher->addClass('Physics 101');
    	
    	$this->view->teacherTitle=$teacher->getTitle();
    	$this->view->teacherName=$teacher->getName();
    	$this->view->teacherBirthDate=$teacher->getBirthDate();
    	$this->view->teacherClasses=$teacher->getClassTitles();

    	$student=new Model_Student();
    	$student->setName('John Doe');
    	$student->setBirthDate('02-02-1990');
    	$student->setStudentId('9912345US');
    	$student->setGender(1);
    	
    	$this->view->studentId=$student->getStudentId();
    	$this->view->studentName=$student->getName();
    	$this->view->studentGender=$student->getGender();
    	$this->view->studentBirthDate=$student->getBirthDate();
    }
}