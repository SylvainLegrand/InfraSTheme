<?php
/* Copyright (C) 2005-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (c) 2013 Philippe SAGOT <courrier@mon-dolibarr.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 */

/**
 *		\file       htdocs/core/antispamimage.php
 *		\brief      Return antispam image
 */
define('NOLOGIN',1);

if (! defined('NOREQUIREUSER'))   define('NOREQUIREUSER',1);
if (! defined('NOREQUIREDB'))     define('NOREQUIREDB',1);
if (! defined('NOREQUIRETRAN'))   define('NOREQUIRETRAN',1);
if (! defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);
if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);

require_once '../main.inc.php';
/*
 * View
 */
header("Content-type: image/png");
session_start();

$val		= '';
$val2		= '';

$alphabet	= array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
array_push($alphabet, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
array_push($alphabet, "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

mt_srand((double)microtime() * 1000000);

// Police et taille des caractères du code
// ---------------------------------------
$font_ttf=DOL_DOCUMENT_ROOT.'/includes/fonts/LinuxLibertine.ttf';
// Initialisation de l'image du captcha
// ------------------------------------
$image_captcha	= imagecreatetruecolor(80, 32);
imagesavealpha($image_captcha, true);
if (empty($image_captcha)){
    dol_print_error('',"Problem with GD creation");
    exit;
	}
// Définition des couleurs du captcha en RVB
// -----------------------------------------
$color_bg=imagecolorallocatealpha($image_captcha, 255, 255, 255, 80);
$color_font[0]=imagecolorallocate($image_captcha,0, 0, 0);  // black
$color_font[1]=imagecolorallocate($image_captcha, 255, 0, 0); // red
$color_font[2]=imagecolorallocate($image_captcha, 0, 0, 255);  // blue
$color_font[3]=imagecolorallocate($image_captcha, 128, 128, 255); // violet
$color_font[4]=imagecolorallocate($image_captcha, 0, 128, 0); // green
$color_font[5]=imagecolorallocate($image_captcha, 128, 128, 128); // grey
$color_font[6]=imagecolorallocate($image_captcha, 255, 0, 255); // magenta
$color_font[7]=imagecolorallocate($image_captcha, 255, 128, 0); //orange
// Remplissage avec la couleur de fond
// -----------------------------------
imagefill($image_captcha, 0, 0, $color_bg);

// Creation du code et écriture au fur et à mesure dans le captcha
// ---------------------------------------------------------------
for($i = 0; $i < 5; $i++){
		$temp			= $alphabet[mt_rand(0, count($alphabet) - 1)];
		$inclinaison	= mt_rand(0, 45)*mt_rand(-1, 1);
		imagettftext($image_captcha, 14, $inclinaison, 6 + ($i * 14), 22, $color_font[mt_rand(0, 7)], $font_ttf, $temp);
        $string			.= $temp;
        }

// Création de l'image et envoi au navigateur
// ------------------------------------------
imagepng ($image_captcha);

// Suppression des ressources de l'image
// -------------------------------------
imagedestroy($image_captcha);

$sessionkey='dol_antispam_value';
$_SESSION[$sessionkey]	= $string;
?>