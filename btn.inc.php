<?php
if (! defined('ISLOADEDBYSTEELSHEET')) die('Must be call by steelsheet'); ?>
/* <style type="text/css" > */

/* ============================================================================== */
/* Buttons for actions                                                            */
/* ============================================================================== */

div.divButAction {
    margin-bottom: 1.4em;
}
div.tabsAction > a.butAction, div.tabsAction > a.butActionRefused, div.tabsAction > a.butActionDelete,
div.tabsAction > span.butAction, div.tabsAction > span.butActionRefused, div.tabsAction > span.butActionDelete {
    margin-bottom: 1.4em !important;
}
div.tabsActionNoBottom > a.butAction, div.tabsActionNoBottom > a.butActionRefused {
    margin-bottom: 0 !important;
}
span.butAction, span.butActionDelete, .button_search, .button_removefilter {
	cursor: pointer;
}
.butAction, .button, input[name="sbmtConnexion"], .butActionDelete, .buttonDelete, .butActionRefused, .butActionNew, .butActionNewRefused, .button-top-menu-dropdown, a.cke_dialog_ui_button {
	cursor: pointer;
	font-family: <?php print $fontlist ?>;
	text-decoration: none;
	text-align: center;
	font-weight: normal;
	display: inline-block;
	padding: 0.6em 0.3125em; /* top+bottom right+left */
	margin: 0em 0.6em !important;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}
.butAction img, .button img, .butActionDelete img, .buttonDelete img, .butActionRefused img, .butActionNew img, .butActionNewRefused img, .button-top-menu-dropdown img {
	max-height: 12px;
}
.butAction, .button, input[name="sbmtConnexion"], .button-top-menu-dropdown, a.cke_dialog_ui_button {
    color: #<?php echo $colortextbackhmenu; ?> !important;
	background-image: -o-linear-gradient(bottom, rgba(<?php echo $colorbackhmenu1; ?>, 0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>, 1) 100%);
	background-image: -moz-linear-gradient(bottom, rgba(<?php echo $colorbackhmenu1; ?>, 0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>, 1) 100%);
	background-image: -webkit-linear-gradient(bottom, rgba(<?php echo $colorbackhmenu1; ?>, 0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>, 1) 100%);
	background-image: -ms-linear-gradient(bottom, rgba(<?php echo $colorbackhmenu1; ?>, 0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>, 1) 100%);
	background-image: linear-gradient(to bottom, rgba(<?php echo $colorbackhmenu1; ?>, 0.3) 0%, rgba(<?php echo $colorbackhmenu1; ?>, 1) 100%);
	background-repeat: repeat-x;
}
.butActionDelete, .buttonDelete, .butActionDelete:link, .butActionDelete:visited, .butActionDelete:hover, .butActionDelete:active, a.cke_dialog_ui_button_cancel {
	color: #4c4c4c !important;
	background-image: -o-linear-gradient(bottom, rgba(<?php echo $colorbacklinepairhover; ?>, 0.3) 0%, rgba(<?php echo $colorbacklinepairhover; ?>, 1) 100%);
	background-image: -moz-linear-gradient(bottom, rgba(<?php echo $colorbacklinepairhover; ?>, 0.3) 0%, rgba(<?php echo $colorbacklinepairhover; ?>, 1) 100%);
	background-image: -webkit-linear-gradient(bottom, rgba(<?php echo $colorbacklinepairhover; ?>, 0.3) 0%, rgba(<?php echo $colorbacklinepairhover; ?>, 1) 100%);
	background-image: -ms-linear-gradient(bottom, rgba(<?php echo $colorbacklinepairhover; ?>, 0.3) 0%, rgba(<?php echo $colorbacklinepairhover; ?>, 1) 100%);
	background-image: linear-gradient(to bottom, rgba(<?php echo $colorbacklinepairhover; ?>, 0.3) 0%, rgba(<?php echo $colorbacklinepairhover; ?>, 1) 100%);
	background-repeat: repeat-x;
}
.button:hover, .buttonDelete:hover, .butAction:hover, .butActionNew:hover, .butActionDelete:hover, .button-top-menu-dropdown:hover, a.cke_dialog_ui_button:hover, .button:focus, .buttonDelete:focus, .butAction:focus, .butActionNew:focus, .butActionDelete:focus, .button-top-menu-dropdown:focus, a.cke_dialog_ui_button:focus {
	/* warning: having a larger shadow has side effect when button is completely on left of a table */
	-webkit-box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
	box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
}
.button_search, .button_removefilter {
    border: unset;
    background-color: unset;
}
.buttonRefused, .butActionRefused, .butActionNewRefused, .button:disabled, .buttonDelete:disabled, .button.disabled {
	pointer-events: none;
    cursor: not-allowed !important;
	opacity: 0.4;
    color: #999 !important;
    box-shadow: none !important;
    -webkit-box-shadow: none !important;
}
.butActionTransparent {
    color: #222 ! important;
    background-color: transparent ! important;
}
a.butActionNew>span.fa-plus-circle, a.butActionNew>span.fa-plus-circle:hover,
span.butActionNew>span.fa-plus-circle, span.butActionNew>span.fa-plus-circle:hover,
a.butActionNewRefused>span.fa-plus-circle, a.butActionNewRefused>span.fa-plus-circle:hover,
span.butActionNewRefused>span.fa-plus-circle, span.butActionNewRefused>span.fa-plus-circle:hover,
a.butActionNew>span.fa-list-alt, a.butActionNew>span.fa-list-alt:hover,
span.butActionNew>span.fa-list-alt, span.butActionNew>span.fa-list-alt:hover,
a.butActionNewRefused>span.fa-list-alt, a.butActionNewRefused>span.fa-list-alt:hover,
span.butActionNewRefused>span.fa-list-alt, span.butActionNewRefused>span.fa-list-alt:hover {
	padding-<?php echo $left; ?>: 6px; font-size: 1.5em; border: none; box-shadow: none; webkit-box-shadow: none;
}

/* ============================================================================== */
/* title Buttons																  */
/* ============================================================================== */

.btnTitle, a.btnTitle {
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px
    font-weight: 400;
    line-height: <?php echo $dropdownLineHeight; ?>;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    box-shadow: none;
    text-decoration: none;
    position: relative;
    margin: 0 0 0 10px;
    min-width: 80px;
    text-align: center;
    color: rgb(<?php print $colortextlink; ?>);
    border: none;
    font-size: 12px;
    font-weight: 300;
    background-color: #fbfbfb;
}
.btnTitle > .btnTitle-icon{

}
.btnTitle > .btnTitle-label{
    color: #666666;
}
.btnTitle:hover, a.btnTitle:hover {
    border-radius: 3px;
    position: relative;
    margin: 0 0 0 10px;
    text-align: center;
    color: #ffffff;
    background-color: rgb(<?php print $colortextlink; ?>);
    font-size: 12px;
    text-decoration: none;
    box-shadow: none;
}
.btnTitle.refused, a.btnTitle.refused, .btnTitle.refused:hover, a.btnTitle.refused:hover {
        color: #8a8a8a;
        cursor: not-allowed;
        background-color: #fbfbfb;
        background: repeating-linear-gradient( 45deg, #ffffff, #f1f1f1 4px, #f1f1f1 4px, #f1f1f1 4px );
}
.btnTitle:hover .btnTitle-label{
    color: #ffffff;
}
.btnTitle.refused .btnTitle-label, .btnTitle.refused:hover .btnTitle-label{
    color: #8a8a8a;
}
.btnTitle>.fa {
    font-size: 20px;
    display: block;
}

<?php if (! empty($conf->global->MAIN_BUTTON_HIDE_UNAUTHORIZED) && (! $user->admin)) { ?>
.butActionRefused, .butActionNewRefused, .btnTitle.refused {
    display: none !important;
}
<?php }
