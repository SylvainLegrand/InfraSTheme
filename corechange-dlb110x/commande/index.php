<?php
/* Copyright (C) 2003-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2019      Nicolas ZABOURI      <info@inovea-conseil.com>
 * Copyright (C) 2019       Frédéric France         <frederic.france@netlogic.fr>
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/commande/index.php
 *	\ingroup    commande
 *	\brief      Home page of customer order module
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/notify.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/client.class.php';
require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';	// InfraS add

if (!$user->rights->commande->lire) accessforbidden();

$hookmanager = new HookManager($db);

// Initialize technical object to manage hooks. Note that conf->hooks_modules contains array
$hookmanager->initHooks(array('ordersindex'));

// Load translation files required by the page
$langs->loadLangs(array('orders', 'bills'));

// Security check
$socid = GETPOST('socid', 'int');
if ($user->socid > 0)
{
	$action = '';
	$socid = $user->socid;
}



/*
 * View
 */

$commandestatic = new Commande($db);
$companystatic = new Societe($db);
$projectstatic=new Project($db);	// InfraS add
$form = new Form($db);
$formfile = new FormFile($db);
$help_url = "EN:Module_Customers_Orders|FR:Module_Commandes_Clients|ES:Módulo_Pedidos_de_clientes";

llxHeader("", $langs->trans("Orders"), $help_url);


print load_fiche_titre($langs->trans("OrdersArea"), '', 'order');	// InfraS change commercial by order


print '<div class="fichecenter"><div class="fichethirdleft">';

if (!empty($conf->global->MAIN_SEARCH_FORM_ON_HOME_AREAS))     // This is useless due to the global search combo
{
    // Search customer orders
    $var = false;
    print '<form method="post" action="'.DOL_URL_ROOT.'/commande/list.php">';
    print '<input type="hidden" name="token" value="'.newToken().'">';
    print '<div class="div-table-responsive-no-min">';
    print '<table class="noborder nohover centpercent">';
    print '<tr class="liste_titre"><td colspan="3">'.$langs->trans("Search").'</td></tr>';
    print '<tr class="oddeven"><td>';
    print $langs->trans("CustomerOrder").':</td><td><input type="text" class="flat" name="sall" size=18></td><td><input type="submit" value="'.$langs->trans("Search").'" class="button"></td></tr>';
    print "</table></div></form><br>\n";
}


/*
 * Statistics
 */

$sql = "SELECT count(c.rowid), c.fk_statut";
$sql .= " FROM ".MAIN_DB_PREFIX."societe as s";
$sql .= ", ".MAIN_DB_PREFIX."commande as c";
if (!$user->rights->societe->client->voir && !$socid) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
$sql .= " WHERE c.fk_soc = s.rowid";
$sql .= " AND c.entity IN (".getEntity('societe').")";
if ($user->socid) $sql .= ' AND c.fk_soc = '.$user->socid;
if (!$user->rights->societe->client->voir && !$socid) $sql .= " AND s.rowid = sc.fk_soc AND sc.fk_user = ".$user->id;
$sql .= " GROUP BY c.fk_statut";

$resql = $db->query($sql);
if ($resql)
{
    $num = $db->num_rows($resql);
    $i = 0;

    $total = 0;
    $totalinprocess = 0;
    $dataseries = array();
    $vals = array();
    // -1=Canceled, 0=Draft, 1=Validated, 2=Accepted/On process, 3=Closed (Sent/Received, billed or not)
    while ($i < $num)
    {
        $row = $db->fetch_row($resql);
        if ($row)
        {
            //if ($row[1]!=-1 && ($row[1]!=3 || $row[2]!=1))
            {
                if (! isset($vals[$row[1]])) $vals[$row[1]]=0;
                $vals[$row[1].$bool]+=$row[0];
                $totalinprocess+=$row[0];
            }
            $total+=$row[0];
        }
        $i++;
    }
    $db->free($resql);
    print '<div class="div-table-responsive-no-min">';
    print '<table class="noborder nohover centpercent">';
    print '<tr class="liste_titre"><th colspan="2">'.$langs->trans("Statistics").' - '.$langs->trans("CustomersOrders").'</th></tr>'."\n";
    $listofstatus=array(0,1,2,3,-1);
    foreach ($listofstatus as $status)
    {
    	$dataseries[]=array($commandestatic->LibStatut($status, $bool, 1), (isset($vals[$status.$bool])?(int) $vals[$status.$bool]:0));
    }
    if ($conf->use_javascript_ajax)
    {
        print '<tr class="impair"><td align="center" colspan="2">';

        include_once DOL_DOCUMENT_ROOT.'/core/class/dolgraph.class.php';
        $dolgraph = new DolGraph();
        $dolgraph->SetData($dataseries);
        $dolgraph->setShowLegend(1);
        $dolgraph->setShowPercent(1);
        $dolgraph->SetType(array('pie'));
		$dolgraph->setWidth('500');	// InfraS change 100% to 500
		$dolgraph->SetHeight('300');	// InfraS add
        $dolgraph->draw('idgraphstatus');
        print $dolgraph->show($total ? 0 : 1);

        print '</td></tr>';
    }
    else
    {
	    foreach ($listofstatus as $status)
	    {
        	print '<tr class="oddeven">';
            print '<td>'.$commandestatic->LibStatut($status, $bool, 0).'</td>';
            print '<td class="right"><a href="list.php?search_status='.$status.'">'.(isset($vals[$status.$bool]) ? $vals[$status.$bool] : 0).' ';
            print $commandestatic->LibStatut($status, $bool, 3);
            print '</a>';
            print '</td>';
            print "</tr>\n";
        }
    }
    //if ($totalinprocess != $total)
    print '<tr class="liste_total"><td>'.$langs->trans("Total").'</td><td class="right">'.$total.'</td></tr>';
    print "</table></div><br>";
}
else
{
    dol_print_error($db);
}


/*
 * Draft orders
 */
if (!empty($conf->commande->enabled))
{
	$sql = "SELECT c.rowid, c.ref, c.fk_projet, s.nom as name, s.rowid as socid";	// InfraS add ", c.fk_projet"
    $sql .= ", s.client";
    $sql .= ", s.code_client";
    $sql .= ", s.canvas";
	$sql .= " FROM ".MAIN_DB_PREFIX."commande as c";
	$sql .= ", ".MAIN_DB_PREFIX."societe as s";
	if (!$user->rights->societe->client->voir && !$socid) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
	$sql .= " WHERE c.fk_soc = s.rowid";
	$sql .= " AND c.entity IN (".getEntity('commande').")";
	$sql .= " AND c.fk_statut = 0";
	if ($socid) $sql .= " AND c.fk_soc = ".$socid;
	if (!$user->rights->societe->client->voir && !$socid) $sql .= " AND s.rowid = sc.fk_soc AND sc.fk_user = ".$user->id;

	$resql = $db->query($sql);
	if ($resql)
	{
        print '<div class="div-table-responsive-no-min">';
		$num = $db->num_rows($resql);	// Infras moved from line 205
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre">';
		print '<th colspan="3">'.$langs->trans("DraftOrders").($num?' <span class="badge">'.$num.'</span>':'').'</th></tr>';	// InfraS change colspan 2 to 3 and add .($num?' <span class="badge">'.$num.'</span>':'')
		$langs->load("orders");
//		$num = $db->num_rows($resql);	InfraS moved to line 200
		if ($num)
		{
			$i = 0;
			$var = true;
			while ($i < $num)
			{
				$obj = $db->fetch_object($resql);

                $commandestatic->id = $obj->rowid;
                $commandestatic->ref = $obj->ref;

				$companystatic->id = $obj->socid;
				$companystatic->name = $obj->name;
				$companystatic->client = $obj->client;
				$companystatic->code_client = $obj->code_client;
				$companystatic->canvas = $obj->canvas;
				$projectstatic->fetch($obj->fk_projet);	// InfraS add

				print '<tr class="oddeven">';
				print '<td class="nowrap">';
				print $commandestatic->getNomUrl(1);
                print "</td>";
                print '<td class="nowrap">';
				print $companystatic->getNomUrl(1, 'company', 16);
                print "</td>";	// InfraS add
                print '<td class="nowrap">';	// InfraS add
				print ($obj->fk_projet > 0 ? $projectstatic->getNomUrl(1) : '');	// InfraS add
                print '</td></tr>';
				$i++;
			}
		}
		else
		{
			print '<tr class="oddeven"><td colspan="3" class="opacitymedium">'.$langs->trans("NoOrder").'</td></tr>';	// InfraS change add class="opacitymedium"
		}
		print "</table></div><br>";
	}
}


print '</div><div class="fichetwothirdright"><div class="ficheaddleft">';


$max = 5;

/*
 * Last modified orders
 */

$sql = "SELECT c.rowid, c.entity, c.ref, c.fk_projet, c.fk_statut, c.facture, c.date_cloture as datec, c.tms as datem,";	// InfraS add ", c.fk_projet"
$sql .= " s.nom as name, s.rowid as socid";
$sql .= ", s.client";
$sql .= ", s.code_client";
$sql .= ", s.canvas";
$sql .= " FROM ".MAIN_DB_PREFIX."commande as c,";
$sql .= " ".MAIN_DB_PREFIX."societe as s";
if (!$user->rights->societe->client->voir && !$socid) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
$sql .= " WHERE c.fk_soc = s.rowid";
$sql .= " AND c.entity IN (".getEntity('commande').")";
//$sql.= " AND c.fk_statut > 2";
if ($socid) $sql .= " AND c.fk_soc = ".$socid;
if (!$user->rights->societe->client->voir && !$socid) $sql .= " AND s.rowid = sc.fk_soc AND sc.fk_user = ".$user->id;
$sql .= " ORDER BY c.tms DESC";
$sql .= $db->plimit($max, 0);

$resql = $db->query($sql);
if ($resql)
{
    print '<div class="div-table-responsive-no-min">';
	print '<table class="noborder centpercent">';
	print '<tr class="liste_titre">';
	print '<th colspan="5">'.$langs->trans("LastModifiedOrders", $max).'</th></tr>';	// InfraS change colspan 4 to 5

	$num = $db->num_rows($resql);
	if ($num)
	{
		$i = 0;
		$var = true;
		while ($i < $num)
		{
			$obj = $db->fetch_object($resql);

			print '<tr class="oddeven">';
			print '<td width="20%" class="nowrap">';

			$commandestatic->id = $obj->rowid;
			$commandestatic->ref = $obj->ref;

			$companystatic->id = $obj->socid;
			$companystatic->name = $obj->name;
			$companystatic->client = $obj->client;
			$companystatic->code_client = $obj->code_client;
			$companystatic->canvas = $obj->canvas;
			$projectstatic->fetch($obj->fk_projet);	// InfraS add

			print '<table class="nobordernopadding"><tr class="nocellnopadd">';
			print '<td width="96" class="nobordernopadding nowrap">';
			print $commandestatic->getNomUrl(1);
			print '</td>';

			print '<td width="16" class="nobordernopadding nowrap">';
			print '&nbsp;';
			print '</td>';

			print '<td width="16" class="nobordernopadding hideonsmartphone right">';
			$filename = dol_sanitizeFileName($obj->ref);
			$filedir = $conf->commande->multidir_output[$obj->entity].'/'.dol_sanitizeFileName($obj->ref);
			$urlsource = $_SERVER['PHP_SELF'].'?id='.$obj->rowid;
			print $formfile->getDocumentsLink($commandestatic->element, $filename, $filedir);
			print '</td></tr></table>';

			print '</td>';

			print '<td class="nowrap">';
            print $companystatic->getNomUrl(1, 'company', 16);
			print "</td>";	// InfraS add
			print '<td class="nowrap">';	// InfraS add
			print ($obj->fk_projet > 0 ? $projectstatic->getNomUrl(1) : '');	// InfraS add
            print '</td>';
			print '<td>'.dol_print_date($db->jdate($obj->datem), 'day').'</td>';
			print '<td class="right">'.$commandestatic->LibStatut($obj->fk_statut, $obj->facture, 5).'</td>';
			print '</tr>';
			$i++;
		}
	}
	print "</table></div><br>";
}
else dol_print_error($db);


/*
 * Orders to process
 */
if (!empty($conf->commande->enabled))
{
	$sql = "SELECT c.rowid, c.entity, c.ref, c.fk_projet, c.fk_statut, c.facture, s.nom as name, s.rowid as socid";	// InfraS add ", c.fk_projet"
    $sql .= ", s.client";
    $sql .= ", s.code_client";
    $sql .= ", s.canvas";
	$sql .= " FROM ".MAIN_DB_PREFIX."commande as c";
	$sql .= ", ".MAIN_DB_PREFIX."societe as s";
	if (!$user->rights->societe->client->voir && !$socid) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
	$sql .= " WHERE c.fk_soc = s.rowid";
	$sql .= " AND c.entity IN (".getEntity('commande').")";
	$sql .= " AND c.fk_statut = 1";
	if ($socid) $sql .= " AND c.fk_soc = ".$socid;
	if (!$user->rights->societe->client->voir && !$socid) $sql .= " AND s.rowid = sc.fk_soc AND sc.fk_user = ".$user->id;
	$sql .= " ORDER BY c.rowid DESC";

	$resql = $db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
        print '<div class="div-table-responsive-no-min">';
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre">';
		print '<th colspan="4">'.$langs->trans("OrdersToProcess").' <a href="'.DOL_URL_ROOT.'/commande/list.php?search_status=1">'.($num?' <span class="badge">'.$num.'</span>':'').'</a></th></tr>';	// InfraS change

		if ($num)
		{
			$i = 0;
			$var = true;
			while ($i < $num)
			{
				$obj = $db->fetch_object($resql);
				print '<tr class="oddeven">';
				print '<td class="nowrap" width="20%">';

				$commandestatic->id = $obj->rowid;
				$commandestatic->ref = $obj->ref;

				$companystatic->id = $obj->socid;
				$companystatic->name = $obj->name;
				$companystatic->client = $obj->client;
				$companystatic->code_client = $obj->code_client;
				$companystatic->canvas = $obj->canvas;
				$projectstatic->id=$obj->fk_projet;	// InfraS add

				print '<table class="nobordernopadding"><tr class="nocellnopadd">';
				print '<td width="96" class="nobordernopadding nowrap">';
				print $commandestatic->getNomUrl(1);
				print '</td>';

				print '<td width="16" class="nobordernopadding nowrap">';
				print '&nbsp;';
				print '</td>';

				print '<td width="16" class="nobordernopadding hideonsmartphone right">';
				$filename = dol_sanitizeFileName($obj->ref);
				$filedir = $conf->commande->multidir_output[$obj->entity].'/'.dol_sanitizeFileName($obj->ref);
				$urlsource = $_SERVER['PHP_SELF'].'?id='.$obj->rowid;
				print $formfile->getDocumentsLink($commandestatic->element, $filename, $filedir);
				print '</td></tr></table>';

				print '</td>';

				print '<td class="nowrap">';
                print $companystatic->getNomUrl(1, 'company', 24);
				print "</td>";	// InfraS add
				print '<td class="nowrap">';	// InfraS add
				print ($obj->fk_projet > 0 ? $projectstatic->getNomUrl(1) : '');	// InfraS add
                print '</td>';

				print '<td class="right">'.$commandestatic->LibStatut($obj->fk_statut, $obj->facture, 5).'</td>';

				print '</tr>';
				$i++;
			}
		}

		print "</table></div><br>";
	}
	else dol_print_error($db);
}

/*
 * Orders thar are in a shipping process
 */
if (!empty($conf->commande->enabled))
{
	$sql = "SELECT c.rowid, c.entity, c.ref, c.fk_projet, c.fk_statut, c.facture, s.nom as name, s.rowid as socid";	// InfraS add ", c.fk_projet"
    $sql .= ", s.client";
    $sql .= ", s.code_client";
    $sql .= ", s.canvas";
	$sql .= " FROM ".MAIN_DB_PREFIX."commande as c";
	$sql .= ", ".MAIN_DB_PREFIX."societe as s";
	if (!$user->rights->societe->client->voir && !$socid) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
	$sql .= " WHERE c.fk_soc = s.rowid";
	$sql .= " AND c.entity IN (".getEntity('commande').")";
	$sql .= " AND c.fk_statut = 2 ";
	if ($socid) $sql .= " AND c.fk_soc = ".$socid;
	if (!$user->rights->societe->client->voir && !$socid) $sql .= " AND s.rowid = sc.fk_soc AND sc.fk_user = ".$user->id;
	$sql .= " ORDER BY c.rowid DESC";

	$resql = $db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);

        print '<div class="div-table-responsive-no-min">';
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre">';
		print '<th colspan="4">'.$langs->trans("OnProcessOrders").' <a href="'.DOL_URL_ROOT.'/commande/list.php?search_status=2">'.($num?' <span class="badge">'.$num.'</span>':'').'</a></th></tr>';	// InfraS change

		if ($num)
		{
			$i = 0;
			$var = true;
			while ($i < $num)
			{
				$obj = $db->fetch_object($resql);
				print '<tr class="oddeven">';
				print '<td width="20%" class="nowrap">';

				$commandestatic->id = $obj->rowid;
				$commandestatic->ref = $obj->ref;

				$companystatic->id = $obj->socid;
				$companystatic->name = $obj->name;
				$companystatic->client = $obj->client;
				$companystatic->code_client = $obj->code_client;
				$companystatic->canvas = $obj->canvas;
				$projectstatic->id=$obj->fk_projet;	// InfraS add

				print '<table class="nobordernopadding"><tr class="nocellnopadd">';
				print '<td width="96" class="nobordernopadding nowrap">';
				print $commandestatic->getNomUrl(1);
				print '</td>';

				print '<td width="16" class="nobordernopadding nowrap">';
				print '&nbsp;';
				print '</td>';

				print '<td width="16" class="nobordernopadding hideonsmartphone right">';
				$filename = dol_sanitizeFileName($obj->ref);
				$filedir = $conf->commande->multidir_output[$obj->entity].'/'.dol_sanitizeFileName($obj->ref);
				$urlsource = $_SERVER['PHP_SELF'].'?id='.$obj->rowid;
				print $formfile->getDocumentsLink($commandestatic->element, $filename, $filedir);
				print '</td></tr></table>';

				print '</td>';

				print '<td>';
				print $companystatic->getNomUrl(1, 'company');
				print "</td>";	// InfraS add
				print '<td class="nowrap">';	// InfraS add
				print ($obj->fk_projet > 0 ? $projectstatic->getNomUrl(1) : '');	// InfraS add
				print '</td>';

				print '<td class="right">'.$commandestatic->LibStatut($obj->fk_statut, $obj->facture, 5).'</td>';

				print '</tr>';
				$i++;
			}
		}
		print "</table></div><br>";
	}
	else dol_print_error($db);
}


print '</div></div></div>';

$parameters = array('user' => $user);
$reshook = $hookmanager->executeHooks('dashboardOrders', $parameters, $object); // Note that $action and $object may have been modified by hook

// End of page
llxFooter();
$db->close();