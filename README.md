# OPERI - Open Periodical Publishing Platform

This software is designed to provide a simple method of publishing Open Access periodicals.  It handles PDF copies of journal articles, and includes a customizable display, as well as options for rich metadata, and searching by title, author, and controlled subject keywords.

## Requirements

Installation of **OPeri** requires installation of PHP and a MySQL Database.  It has been tested on PHP 5.3, 5.4 and 5.6k and using Apache web servers on Windows and various Linux platforms including Ubuntu, RedHat, and Debian

Use of the administrative tools will also require you have a functioning mail server.

In your php config file you will also likely need to turn on output buffering.

    output_buffering = 4096
    
should work.

If you are working in XAMPP, you may also need to set

    extension=php_fileinfo.dll
    
in your php.ini file in order be able to upload files within the application.

## Installation

Create a mysql database named "ppub" and load the `ppub.sql` file.  You may wish to name it something else, however you will need to change the parameters in config file

Database configuration can be found in `includes/dbconnect.php`

The software should work on most servers, however, you may need to modify permissions for file uploads (e.g. some servers require you modify any upload directories to be owned by the server itself).


### Getting Started

Once installed, front-end views of the publication can be viewed at the `/` level of whichever directory you have set the files.  Adminstration can be accessed at `[sitename]/admin`

To get started configuring the site, you will need to take a few steps.

Navigate to the `/admin` directory. 

The default login information is:

    userame:  admin
    password: admin

You will want to change this relatively soon.  However before you can create a new account, you will need to navigate to **"Site Management."**
 
From here you will need to first click on **"Update Domain Information."**  Set domain to be location of the directory where Operi will live.  

_**Note:** This is important, as it will be used for setting up the link for setting up password resetting.*_

After this, go to ****"Manage Users."****  Here you can set up a new account.** 

This will send an email to the new user, who will then be prompted to change their password.
 
After a new more secure administrator account has been created, it is recommended to delete the default "admin" account.**

*If you have chosen to restrict all users to live at one specific domain, for security purposes, you will only need to add the username associated with that domain.  Otherwise (in most cases) you will need to use a full email address.

*You may notice that administrators cannot delete or remove administrative ability for their own accounts.  This is to help ensure that you do not accidentally lock yourself out of the back end.

At this point, in the "Site Management" area, you can set up the configuration of the site, including the name, change the header logo, and the graphical look through a custom css tool.

##### More to Come

While much of this may be self-explanatory, a more detailed manual should be available soon... 
