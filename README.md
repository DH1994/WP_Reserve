# WP_RESERVE 
****
*This is a reserve system for Wordpress. You can create tables where users can reserve places. In the admin page you can drag and drop tables where you want. In wordpress you can set featured image as background.*

# Installation

##### Add template 
To install this in wordpress, you have to copy the files reserve/reserveadmin to your theme folder. Then you have to create a page and add it as page template. 

##### Add css
Copy reserve.css to css folder in the theme folder. Change the path in the follow line: 
</br></br>
 link rel="stylesheet" href="wp-content/themes/DivisionGamingLanParty/css/reserve.css">
</br></br>
You have to do this for reserve as reserveadmin too.

##### Add jquery
If jquery isn't available in your worpress folder, download it and change the following lines in reserve/reserveadmin.
</br></br>
link rel="stylesheet" href="jquery-ui.css":
</br>
script src="jquery-1.10.2.js"></script>
</br>
script src="jquery-ui.js"></script>

##### Install Plugins
You have to install the following plugins in wordpress:
</br></br>
Events Manager</br>
* Supercharge the Events Manager free plugin with extra feature to make your events even more successful!

wp members</br>
* A user, role, and content management plugin for controlling permissions and access. A plugin for making WordPress a more powerful CMS.


##### Create table in your database
Create a table in your wordpress DB with the name tables and the following fields: </br></br>
 id 	width 	height 	x 	y 	rows 	columns 	color 	degrees 	permissions 	event_id
 
##### Create roles
Create roles in the members plugin and edit reserveadmin/reserve where if(current_user_can('reserve_admin')). Change reserve admin to your role.

##### Event
Set event id in page settings in wordpress. You have to add a custom field with name id. 
