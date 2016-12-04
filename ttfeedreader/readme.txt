Plugin Name : TT Feed Reader

Necessary to Setup 

Instal plugin by zip or extract it and copy plugin folder to Wordpress Plugin folder
Follow the instructions to install plugin WP JSON API v1
Permalink should be Postname. you can change it by Setting > Permalink

After installing a custom menu created as name Json Feeds in wordpress admin menu
In this you will see a custom post type sections and as well as Menu items of Ajax and Background
Import Processes.

Plugin Based on Three Feed Processes 

First Process

Import Ajax => Feed Imports by Ajax Request via JQuery Batching 

After Putting JSON Objects URL You can Test URL by clicking on Test Feed URL than
click on Import Feed Product button to start Import Process

First Request create json files by url in files folder at plugin root directory
Second Request start processing (importing) these first step files from files folder to 
json encode and create custom post types enteries.

If this Process disturbed by something like Internet disconnectivity than their appear
a button Import Remaining Feeds to Import all feeds from files folder to Database (WP Custom Type)

All Process Progress is showing at frontend Progress bar with % completed 

Now Second Process 

Import Background => This Process have 4 flags ("pending, import, wrong, success")

In this Section you can add URL by a Feed name to Database with pending status and By Default it will be processed by WP cron 
in Background but you can also Click on Button Process Now to continue Importing by yourself with PHP redirect
batching. It will create files into folder direct_files and than start importing files 20 files per redirect.
you can also see progress of it at frontend progress bar. as well as default cron process do the same thing but in background
and importing process will be 250 files per wp cron call to function. approx It will import 50K feeds per hour

You can edit , Test URL and Delete URL entry any time

If URL contains no json objects it will set status to "wrong" in database
If files created by correct json URL in direct or background process it will set status to import
If File Imported successfully it will set status to success
Default status is pending

Frontend Products Display

Home Page and Single (Detail Product Page) are rendered by Angular JS with WP JSON API calls.
HTML 5 is used for frontend display with Bootstrap framework.

Home Page Products are working on Load More Logic
 