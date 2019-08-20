<?php
if (! defined('ISLOADEDBYSTEELSHEET')) die('Must be call by steelsheet'); ?>

/*
 * Dropdown
 */
.open>.dropdown-menu{ /*, #topmenu-login-dropdown:hover .dropdown-menu*/
    display: block;
}
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 0;
    font-size: <?php echo $topMenuFontSize; ?>;
    text-align: left;
    list-style: none;
    background-color: rgb(<?php echo $colorbackbody; ?>);
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.2);
    box-shadow: 0 6px 12px rgba(0,0,0,.2);	
}
/*
* MENU Dropdown
*/
.login_block.usedropdown .logout-btn{
    display: none;
}
.tmenu .open.dropdown, .login_block .open.dropdown, .tmenu .open.dropdown, .login_block .dropdown:hover{
    background: rgba(0, 0, 0, 0.1);
}
.tmenu .dropdown-menu, .login_block .dropdown-menu {
    position: absolute;
    right: 0;
    <?php echo $left; ?>: auto;
    line-height: <?php echo $dropdownLineHeight; ?>;
}
.tmenu .dropdown-menu, .login_block  .dropdown-menu .user-body {
}
.user-body {
    color: #333;
}
.side-nav-vert .user-menu .dropdown-menu {
    padding: 0;
    border-top-width: 0;
    width: 300px;
}
.side-nav-vert .user-menu .dropdown-menu {
    margin-top: -1px;
}

.side-nav-vert .user-menu .dropdown-menu > .user-header {
    height: 175px;
    padding: 10px;
    text-align: center;
    white-space: normal;
}
.dropdown-user-image {
    border-radius: 50%;
    vertical-align: middle;
    z-index: 5;
    height: 90px;
    width: 90px;
    border: 3px solid;
    border-color: transparent;
    border-color: rgba(255, 255, 255, 0.2);
    max-width: 100%;
    max-height :100%;
}
.dropdown-menu > .user-header{
	background-image: -o-linear-gradient(top, rgba(<?php echo $colorbackhmenu1; ?>,0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>,1) 100%);
	background-image: -moz-linear-gradient(top, rgba(<?php echo $colorbackhmenu1; ?>,0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>,1) 100%);
	background-image: -webkit-linear-gradient(top, rgba(<?php echo $colorbackhmenu1; ?>,0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>,1) 100%);
	background-image: -ms-linear-gradient(top, rgba(<?php echo $colorbackhmenu1; ?>,0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>,1) 100%);
	background-image: linear-gradient(to top, rgba(<?php echo $colorbackhmenu1; ?>,0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>,1) 100%);
	background-repeat: repeat-x;
}
.dropdown-menu > .user-footer {
    padding: 10px;
}
.user-footer:after {
    clear: both;
}
.dropdown-menu > .user-body {
    padding: 15px;
    border-bottom: 1px solid #f4f4f4;
    border-top: 1px solid #dddddd;
    white-space: normal;
}
#topmenu-login-dropdown{
    padding: 0 5px 0 5px;
}
#topmenu-login-dropdown a:hover{
    text-decoration: none;
}
#topmenuloginmoreinfo-btn{
    display: block;
    text-align: right;
    color:#666;
    cursor: pointer;
}
#topmenuloginmoreinfo{
    display: none;
    clear: both;
    font-size: <?php echo $topMenuFontSize; ?>;
}