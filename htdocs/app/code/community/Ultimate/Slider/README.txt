/**
 * Ultimate_Slider extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Images & Media
 * @package        Ultimate_Slider
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Ultimate_Slider Extension Readme
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author 		Rajasingh and Manikandan D
 */

Before Installation:
--------------------
Put below line in your htaccess file.

php_value upload_max_filesize 80M

Note: This will only allow the video file to uploaded with higher size.


Direct/Manual Installation:
---------------------------

Step 1: Extract the zip file to a flexible location.
Step 2: Copy the folders app and media alone to your Magento project root directory, It will ask you for merging folders and files. Proceed with that.
Step 3: Login to your admin panel of your Magento project.
Step 4: Go to System->Cache Management.
Step 5: Select all options and in the actions with refresh selected, click submit.
Step 6: Flush Magento Cache, You will be now seeing the installed extension.

For Other Type of Installation:
-------------------------------
Proceed with magento connect.

Access Frontend on
------------------
Live: http://www.example.com/ultimate_slider
Local: http://localhost/your-magento-project-folder/ultimate_slider

How to Use it:
--------------
To show this slider on home page or any othe location on your magento Site, you have to put this line of code in homepage cms in admin or in static block to to call any where in the site:

{{block type="ultimate_slider/slider_list" name="slider_list" template="ultimate_slider/slider/list.phtml"}}

Hurray..! You're done..! Happy Coding..!

Release Info: Ultimate_Slider 1.0.6
-----------------------------------
# Added Feature of Slider Autoplay
# Fixed bug of items per slide option in the admin panel.
# Fixed bug of namespace and module name errors.
# Fixed bug on active status in the frontend.
# Fixed bug on sample data of Iframe and Mp4 video.
# Fixed bug of native coding errors.

Release Info: Ultimate_Slider 1.0.1
-----------------------------------
# Revised

Release Info: Ultimate_Slider 1.0.0
-----------------------------------
# First Stable Release

