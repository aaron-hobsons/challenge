# Code Challenge

## Overview:

I based this code challenge on a generic Zend Framework 1.12 application. As such there is a lot of extra code -- code that I did not write. The code that I did write is located in the following locations:
/hobsons/applications/models/ -- These are models that I created
/hobsons/tests/application/models -- These are the tests based on these models
/hobsons/application/controllers/IndexController.php -- A very, very basic controller
/hobsons/application/views/index/index.phtml -- A very, very basic view
/hobsons/library/H -- Some code that I wrote a while back to create models in the Zend Framework
/hobsons/sql -- An ERD and the SQL to generate tables for the application

## Question 1: Object Oriented Principles

The code for question one is in the /hobsons/application/models directory. In particular, see the Person.php, Student.php and Teacher.php files. 

You'll notice that both of the classes Model_Student and Model_Teacher extend the abstract class Model_Person which in turn extends H_Model. H_Model is a class I wrote a while back in order to compensate for the Zend Framework's lack of model class. It uses the adapter pattern and its function is to separate the mechanics of storage from the more important model functions like validation. In this case, a model is generally backed by a Zend_Db_Table class, but it really could be any type of storage class as long as it implments the interface properly.

One thing I'm not too happy about in this answer is the printinfo() method -- really that sort of function should be left out of a model, but since you asked for it, I included it anyway.


## Question 2: Testing

Some working tests are located in /hobsons/tests/application/models. I used PHP Unit to build these.


## Question 3: Validation

There is a validation method in the Model_Student class called isValidStudentId(). My H_Model class also has the ability to attach validators using Zend_Filter_Input, but for the sake of clarity, I left those out.


## Question 4: Frameworks/DB Design

I used MySQL Workbench to design a schema that will work for this application. I've included a PDF of an ERD as well as the SQL that was forward engineered using the Workbench. See the /hobsons/sql directory for both.

The database design is a bit more complicated than the challenge needed, but having worked in a university before, I realize that the Person/Teacher/Class model is really too simplistic. Specific embellishments include:
1. 'Class' has been renamed 'course' as it is less likely to cause confusion
2. Courses have sections since a course can be taught more than once
3. Both students and teachers can be associated with sections -- both relationships are many to many.
4. I've added reference tables for title and gender

The method to load a single student's data is in called getStudentById() in Model_Student, which then calls H_Model_Table's getById() method (/hobsons/library/H/Model/Table.php) which in turn uses Zend_Db_Table's find() method.


## Question 5: MVC Model-View-Control Architecture

Since I used the Zend Framework as a basis for this challenge, MVC is already implemented as follows:
/hobsons/applications/models/ -- Models
/hobsons/application/controllers/IndexController.php -- A controller
/hobsons/application/views/index/index.phtml -- A view
