<?php
#=======================================================================#
# PHP-Nuke Titanium : Enhanced and Advanced                             #
#=======================================================================#
# This file displays the content of your menu.                          #
#                                                                       #
# Titanium Portal Menu                                                  #
#=======================================================================#
# This program's license is General Public License                      #
# http://www.gnu.org/licenses/gpl.txt                                   #
#=======================================================================#
return;
if(!defined('NUKE_TITANIUM')) exit;

       global $db, 
		   $admin, 
		    $user, 
	      $prefix, 
  $network_prefix, 
          $cookie, 
	  $def_module, 
	    $bgcolor1, 
		$bgcolor2, 
		$bgcolor3, 
		$bgcolor4,
      $userpoints, 
             $uid;
			 
		 

if(@file_exists(NUKE_LANGUAGE_DIR.'Menu/lang-'.$currentlang.'.php')) 
{
    include_once(NUKE_LANGUAGE_DIR.'Menu/lang-'.$currentlang.'.php');
} 
else 
{
    include_once(NUKE_LANGUAGE_DIR.'Menu/lang-english.php');
}

$managment_group = 1; 
$detectPM=1; 
$detectMozilla=0;
$horizontal=0;
$div=0;

$sql="SELECT t1.invisible, t1.dynamic, t2.main_module FROM ".$prefix."_menu AS t1, ".$prefix."_main AS t2 WHERE t1.groupmenu=99 limit 1";

$result = $db->sql_query($sql);

$row = $db->sql_fetchrow($result);

$main_module = $row['main_module'];

$general_dynamic=($row['dynamic']=='on') ? 1 : 0 ;

$type_invisible=$row['invisible'];

if($managment_group==1) 
{
	$managment_group = ($row['invisible']=="4" || $row['invisible']=="5") ? 1 : 0 ;
}
else 
{
	$managment_group=0;
}

$is_admin = (is_admin($admin)) ? 1 : 0 ;

$is_user = (menu_is_user($user,$managment_group)) ? 1 : 0 ; 

$userpoints=intval($userpoints); 

$ThemeSel = menu_get_theme($is_user); 

$path_icon = "images/menu";

$imgnew = "new.gif";

//this is the start of the Portal menu
$sql = "SELECT * FROM ".$prefix."_modules WHERE active='1' AND inmenu='1' ORDER BY custom_title ASC";
	
	$modulesaffiche= $db->sql_query($sql);
	
	$menu_counter=0;
	
	while ($tempo = $db->sql_fetchrow($modulesaffiche)) 
	{
		$module[$menu_counter]= $tempo['title'];
		$customtitle[$menu_counter] = (stripslashes($tempo['custom_title'])); //strip the fucking slashes
		$view[$menu_counter] = $tempo['view'];
		$active[$row['title']] = $tempo['active'];
		$mod_group[$menu_counter] = ($managment_group==1 && isset($tempo['mod_group'])) ? $tempo['mod_group'] : "";
		$nsngroups[$menu_counter]=(isset($tempo['groups'])) ? $tempo['groups'] : "" ; 
		$gt_url[$menu_counter]=(isset($tempo['url'])) ? $tempo['url'] : "" ; 
	
		$menu_counter++;
	
		if($tempo['view']==3) 
		{ 
		   $gestionsubscription="yes";
		}
	}

    $ferme_sublevels="";
    $total_actions="";
    $flagmenu = 0;  
				
	
	$sql2= "SELECT groupmenu, 
	                  module, 
					     url, 
					url_text, 
					   image, 
					     new, 
				    new_days, 
					   class, 
					    bold, 
					sublevel, 
				  date_debut, 
				    date_fin, 
					    days 
						
						FROM ".$prefix."_menu_categories ORDER BY id ASC";
						
	$result2= $db->sql_query($sql2);
	
	$menu_counter=0;
	
	$totalcompteur=0;
	
	$premier=0;
	
	$hidden=0;
	
	$hidden_sublevel=0;
	
	$now=time(); 
	
	while ($row2 = $db->sql_fetchrow($result2)) 
	{
	   if(strpos($row2['days'],'8')!==false || $now<$row2['date_debut'] || ($row2['date_fin']>0 && $now>$row2['date_fin'])) 
	   {
			if($menu_counter2!=$row2['groupmenu']) 
			{
				$hidden_sublevel=0;
			}
				$hidden=1;
			
				if($hidden_sublevel==0) 
				{
					$hidden_sublevel=$row2['sublevel'];
				}
				else 
				{
					$hidden_sublevel=($row2['sublevel']<$hidden_sublevel) ? $row2['sublevel'] : $hidden_sublevel;
				}
				
			continue;
		}
		
		
		if($row2['module']=="MENUTEXTONLY" 
		|| ($row2['module']=="External Link" 
		&& !stristr("^modules.php\?name=", $row2['url']) 
		&& !stristr("^((http(s)?)|(ftp(s)?))://".$_SERVER['SERVER_NAME']."/modules.php\?name=",$row2['url']))) 
		{
			$poster_module=1;
		}
		else 
		{ 
			$poster_module=0;
			$restricted_reason="";
		
			foreach ($module as $key => $this_module) 
			{
				if($row2['module']=="External Link") 
				{
					$temponomdumodule=split("&", $row2['url']);
				
					if(strstr("^((http(s)?)|(ftp(s)?))://".$_SERVER['SERVER_NAME']."/modules.php\?name=",$row2['url'])) 
					{ 
						$nomdumodule = substr(strstr($temponomdumodule[0],'modules.php'),17);
						$targetblank="target=\"_tab\"";
					}
					else
					if(stristr("^((http(s)?)|(ftp(s)?))://".$_SERVER['SERVER_NAME']."/modules.php\?name=",$row2['url'])) 
					{ 
						$nomdumodule = substr(strstr($temponomdumodule[0],'modules.php'),17);
						$targetblank="";
					}
					else 
					{
						$nomdumodule = str_replace("modules.php\?name=","",$temponomdumodule[0]);
						$targetblank="";
					}
					
					$customtitle2 = (stripslashes($row2['url_text']));
					$urldumodule = $row2['url'];
				}
				else {//module normal
					$temponomdumodule=array();
					$targetblank="";
					$nomdumodule =$row2['module'];
					$fix_customtitle2 = ($customtitle[$key] != "") ? $customtitle[$key] : str_replace("_", " ", $this_module);
					$customtitle2 = (stripslashes($fix_customtitle2));
					$urldumodule = ($gt_url[$key]!="") ? $gt_url[$key] : "modules.php?name=".$nomdumodule ; 
				}
				
				if(!($this_module==$main_module && $row2['module']!="External Link")) //remove the home module from the menu links list!
				{                                                                     //I guess if your french this is a great idea! 
					if(($is_admin===1 AND $view[$key] == 2) OR $view[$key] != 2) 
					{ 
						if($nomdumodule==$this_module) 
						{ 
							$isin=0;
							
							if($is_user==1 && $view[$key]==1 && $type_invisible==4 && $isin==0) 
							{
								$poster_module=2;
								$restricted_reason=""._MENU_RESTRICTEDGROUP."";
								break;
							}
							else
							if($is_user==0 && $view[$key]==1 && ($type_invisible==2 || $type_invisible==4)) 
							{
								$poster_module=2;
								$restricted_reason=""._MENU_RESTRICTEDMEMBERS."";
								break;
							}

							if($is_user==1 && $view[$key]==1 && $type_invisible==5 && $isin==0 && $is_admin==0) 
							{ 
								if($menu_counter2!=$row2['groupmenu']) 
								{
									$hidden_sublevel=0;
								}
								$hidden=1;
								
								if($hidden_sublevel==0) 
								{
									$hidden_sublevel=$row2['sublevel'];
								}
								else 
								{
									$hidden_sublevel=($row2['sublevel']<$hidden_sublevel) ? $row2['sublevel'] : $hidden_sublevel;
								}
							}
							else
							if($is_user==0 && $view[$key]==1 && ($type_invisible==5 || $type_invisible==3) && $is_admin==0) 
							{
								if($menu_counter2!=$row2['groupmenu']) 
								{
									$hidden_sublevel=0;
								}
								$hidden=1;
								
								if($hidden_sublevel==0) 
								{
									$hidden_sublevel=$row2['sublevel'];
								}
								else 
								{
									$hidden_sublevel=($row2['sublevel']<$hidden_sublevel) ? $row2['sublevel'] : $hidden_sublevel;
								}
							}
							else
							if($view[$key]>3 && ($type_invisible==3 || $type_invisible==5) && !in_groups($nsngroups[$key])) 
							{
								if($menu_counter2!=$row2['groupmenu']) 
								{
									$hidden_sublevel=0;
								}
								$hidden=1;
								
								if($hidden_sublevel==0) 
								{
									$hidden_sublevel=$row2['sublevel'];
								}
								else 
								{
									$hidden_sublevel=($row2['sublevel']<$hidden_sublevel) ? $row2['sublevel'] : $hidden_sublevel;
								}
							}
							else 
							{
								$poster_module=1;
							}
							
							break;
						}
					}
				}
			}
		}
		
		if($poster_module > 0) 
		{
			$categorie=$row2['groupmenu'];
			$totalcategorymodules[$totalcompteur]=$row2['module'];
			$totalcompteur++;
			
			if($premier==0) 
			{
				$premier++;
				$total_actions="menu_showhide('menu-".$row2['groupmenu']."','nok','menuupdown-".$row2['groupmenu']."');";
			}
			else
			if($menu_counter2==$categorie) 
			{ 
			  $menu_counter++;
			}
			else 
			{
				$total_actions=$total_actions."menu_showhide('menu-".$row2['groupmenu']."','nok','menuupdown-".$row2['groupmenu']."');";
				$menu_counter=0;
				$hidden_sublevel=0;
				$hidden=0;
			}
							
			if($menu_counter==0 && $row2['sublevel']>0) { 
				$hidden=1;
				$hidden_sublevel=0;
				$row2['sublevel']=0;
			}
			elseif($row2['sublevel']>$hidden_sublevel && $hidden==1) {
				$row2['sublevel']=$row2['sublevel']-$hidden_sublevel;
				if($hidden_sublevel==0) {
					$row2['sublevel']--;
				}
			}
			else {
				$hidden_sublevel=0;
				$hidden=0;
			}

			$moduleinthisgroup[$categorie][$menu_counter]=$row2['module'];
			$linkinthisgroup[$categorie][$menu_counter]=$row2['url'];
			$linktextinthisgroup[$categorie][$menu_counter]=$row2['url_text'];
			$imageinthisgroup[$categorie][$menu_counter]=$row2['image'];
			$newinthisgroup[$categorie][$menu_counter]=$row2['new'];
			$newdaysinthisgroup[$categorie][$menu_counter]=$row2['new_days'];
			$classinthisgroup[$categorie][$menu_counter]=$row2['class'];
			$grasinthisgroup[$categorie][$menu_counter]=$row2['bold'];
			$sublevelinthisgroup[$categorie][$menu_counter]=$row2['sublevel'];
			$date_debutinthisgroup[$categorie][$menu_counter]=$row2['date_debut'];
			$date_fininthisgroup[$categorie][$menu_counter]=$row2['date_fin'];
			$daysinthisgroup[$categorie][$menu_counter]=$row2['days'];
			$nomdumoduleinthisgroup[$categorie][$menu_counter]=$nomdumodule;
			$targetblankinthisgroup[$categorie][$menu_counter]=$targetblank;
			$customtitle2inthisgroup[$categorie][$menu_counter]=$customtitle2;
			$urldumoduleinthisgroup[$categorie][$menu_counter]=$urldumodule;
			$poster_moduleinthisgroup[$categorie][$menu_counter]=$poster_module;
			$whyrestricted[$categorie][$menu_counter]=$restricted_reason;
			$restricted_reason="";
			
			$menu_counter2=$categorie;
		}
	}

$content ="
<!--  Titanium Portal Menu v.3.0 b1  -->";
?>
<script type="text/javascript" language="JavaScript">
function menu_listbox(page) {
	var reg= new RegExp('(_menu_targetblank)$','g');
	if(reg.test(page)) {
		page=page.replace(reg,"");
		window.open(page,'','menubar=yes,status=yes, location=yes, scrollbars=yes, resizable=yes');
	}else if(page!="select") {
			top.location.href=page;
	}
}				
function menu_over_popup(page,nom,option) {
	window.open(page,nom,option);
}
</script>
<style type="text/css">
.menunowrap {white-space: nowrap;}
</style>
<?php
	$dynamictest=0;
	global $prefix, $network_prefix, $db;

    $sql = "SELECT groupmenu, 
	                    name, 
					   image, 
					    lien, 
						  hr, 
					  center, 
					 bgcolor, 
				   invisible, 
				       class, 
					    bold, 
						 new, 
					 listbox, 
					 dynamic, 
				  date_debut, 
				    date_fin, 
					    days 
	
	FROM ".$prefix."_menu ORDER BY groupmenu ASC";
    
	$result = $db->sql_query($sql);
	
	global $textcolor1,
	       $textcolor2,
		  $portaladmin, 
	   $network_prefix, 
	      $avatarwidth, 
		       $domain, 
			      $uid, 
			 $ThemeSel;
	
    list($portaladminname, 
	              $avatar, 
				   $email) = $db->sql_ufetchrow("SELECT `username`,`user_avatar`, `user_email` FROM `".$network_prefix."_users` WHERE `user_id`='$portaladmin'", SQL_NUM);

    
	if (strcmp($_SERVER['SERVER_NAME'], 'cvs.86it.us') == 0)
	{
	  $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"#004400\"><b><center>PHP-Nuke Titanium</center></b></font></div>";
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"#000000\"><b><center><font color=\"$textcolor2\">C</font>oncurrent <font color=\"$textcolor2\">V</font>ersions <font color=\"$textcolor2\">S</font>ystem</center></b></font></div>";
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"$textcolor2\"><center><b>https://".$_SERVER['SERVER_NAME']."</b></center></font></div>";
	}
    else
	if (strcmp($_SERVER['SERVER_NAME'], 'music.86it.us') == 0)
	{
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"#004400\"><b>The 86it Social Network</b></font></div>";
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"$textcolor2\"><b>Titanium Tunes</b></font></div>";
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"$textcolor2\"><center><b>https://".$_SERVER['SERVER_NAME']."</b></center></font></div>";
	}
	else
	{
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"#004400\"><b>$portaladminname</b></font></div>";
      $content.= "<div class=\"supersmall\" align=\"center\"><font color=\"$textcolor2\"><b>Owns This 86it Portal</b></font></div>";
	}
	
    global $facebook_plugin_width; 
    global $admin_icon_image_height, $survey_blocks_table_width, $admin_icon_table_width, $avatarwidth, $main_blocks_table_width, $blocks_width, $innertitle;	
	
	$content.="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	$content.="<tr><td width=\"100%\"></td><td id=\"menu_block\"></td></tr>";
	
	if($horizontal==1) 
	{
		$content.="<tr>";
	}
	
	$classpointeur=0;
    
	while ($row = $db->sql_fetchrow($result)) 
	{  
		$som_groupmenu = $row['groupmenu'];
		$som_name = str_replace("&amp;nbsp;","&nbsp;",$row['name']); 
		$som_image = $row['image'];
		$som_lien = $row['lien'];
		$som_hr = $row['hr'];
		$som_center = $row['center'];
		$som_bgcolor = $row['bgcolor'];
		$invisible[$classpointeur] = $row['invisible'];
		$categoryclass[$classpointeur] = $row['class'];
		$som_bold = $row['bold'];
		$som_new = $row['new'];
		$som_listbox = $row['listbox'];
		$som_dynamic = ($general_dynamic==0) ? '' : $row['dynamic']; 
		$som_date_debut=$row['date_debut'];
		$som_date_fin=$row['date_fin'];
		$som_days=$row['days'];
		$key=$row['groupmenu'];
		
		if(strpos($som_days,'8')!==false || $now<$som_date_debut || ($som_date_fin>0 && $now>$som_date_fin)) 
		{
			
			$aenlever="menu_showhide\('menu-".$som_groupmenu."','nok','menuupdown-".$som_groupmenu."'\);";
			$total_actions = str_replace("$aenlever", "" , $total_actions);
			continue;
		}
		
		if($som_dynamic!='on') 
		{
			$aenlever="menu_showhide\('menu-".$som_groupmenu."','nok','menuupdown-".$som_groupmenu."'\);";
			$total_actions = str_replace("$aenlever", "" , $total_actions);
		}
		
		if($general_dynamic==1 && $dynamictest!=1 && $detectMozilla!=1) 
		{
			//$dynamic=1;
			?>
			<script type="text/javascript" language="JavaScript">
			var keymenu;
			function menu_showhide(tableau, trigger, somimagename) {
				if(document.getElementById(tableau) && document.images[somimagename] && document.getElementById(tableau).style.display == "none" && trigger!="nok") {
					var menu_block=document.getElementById('menu_block');
					document.getElementById(tableau).style.display = "<?php if($div==1) {echo "";} ?>";
					document.images[somimagename].src="<?php echo $path_icon;?>/admin/up.gif";
				}
				else if(document.getElementById(tableau) && document.images[somimagename]) {
					var reg= new RegExp("<?php echo $path_icon;?>/admin/up.gif$","gi");
					if(reg.test(document.images[somimagename].src)) {
						document.images[somimagename].src="<?php echo $path_icon;?>/admin/down.gif";
					}
					document.getElementById(tableau).style.display = "none";
				}
			}
			</script>
			<?php
		}
		$dynamictest=1;
		
		if($som_hr == "on" && $horizontal!=1) 
		{
			$content.="<tr><td><hr width=\"100%\"></td></tr>"; // 15 mars 2005 : adjust the width to 100%
		}

		if($som_groupmenu <> 99) 
		{
			
		  if($som_dynamic=='on' && $detectMozilla!=1 && isset($moduleinthisgroup[$som_groupmenu]['0']) && $som_listbox!="on") 
		  { 
				$reenrouletout=str_replace("menu_showhide\(\'menu-$som_groupmenu\',\'nok\',\'menuupdown-$som_groupmenu\'\);","",$total_actions);
				$action_somgroupmenu="onclick=\"keymenu=".$key.";".$reenrouletout." menu_showhide('menu-$som_groupmenu','ok','menuupdown-$som_groupmenu')\" style=\"cursor:pointer\"";            // menu dynamic
			}
			else 
			{
			  $action_somgroupmenu="";
			}
			if($horizontal==1) {
				$content.="<td bgcolor=\"$som_bgcolor\" width=\"4\"></td>
				<td bgcolor=\"$som_bgcolor\" class=\"menunowrap\" valign=\"top\"><table class=\"menunowrap\"><tr><td $action_somgroupmenu>";	
			}
			else {
					
				$positioningtd = ($div==1) ? "" : "" ;
					
			$content.="
						<tr bgcolor=\"$som_bgcolor\"><td height=\"4\" width=\"100%\"></td><td id=\"menu_divsublevel$key\"></td></tr>
						<tr><td bgcolor=\"$som_bgcolor\" class=\"menunowrap\" width=\"100%\" $action_somgroupmenu>";
			}
			
			if($som_center=="on") {
				$content.="<div align=\"center\">";
			}
			if($som_lien<>"") {
				if(strpos($som_lien,"LANG:_")===0) { // gestion multilingue
					$som_lien = str_replace("LANG:","",$som_lien);
					eval( "\$som_lien = $som_lien;");
				}//fin gestion multilingue
				$testepopup=strpos($som_lien,"javascript:window.open(");
				if($testepopup===0) {
					$som_lien = str_replace("window.open","menu_over_popup",$som_lien);
					$content.="<a href=\"$som_lien\"";
				}
				else {
				$content.="<a href=\"$som_lien\"";
				$testehttp=strpos($som_lien,"http://");
				$testehttps=strpos($som_lien,"https://");
				$testeftp=strpos($som_lien,"ftp://");
				if($testehttp===0 || $testeftp===0 || $testehttps===0) {
					$content.=" target=\"_tab\"";
				}
				$content.=">";
				}
			}

			if($som_image<> "noimg") {
/************************************************************************************/
/*                 Modifications par MAC06  17/07/2003                              */
/*                  http://visiondesign.free.fr                                     */
/*                     magetmac06@hotmail.com                                       */
/*  Les modifs permettent d'inserer soit un swf (Flash), soit une image normale.    */
/*  Les images et les swf doivent etre plac�s dans "images/menu/".              */
/************************************************************************************/
				if(stristr(".swf",$som_image)) { //////////////////// support des fichiers FLASH - par MAC06 //////////////////////////
					$content .= "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"179\" height=\"20\" id=\"$som_groupmenu\"><PARAM NAME=movie VALUE=\"$path_icon/$som_image\"><PARAM NAME=quality VALUE=high><EMBED src=\"$path_icon/$som_image\" quality=high WIDTH=\"160\" HEIGHT=\"20\" TYPE=\"application/x-shockwave-flash\" wmode=\"transparent\"></EMBED></OBJECT><br>";
        		}
				else {
				$fermebalise= ($som_lien!="") ? "</a>" : "" ;
					$content.="<img align=\"texttop\" src =\"$path_icon/$som_image\" border=\"0\" alt=\"$som_image\">".$fermebalise."&nbsp;";
				}
			}

			if(strpos($som_name,"LANG:_")===0) {
				$som_name = str_replace("LANG:","",$som_name);
				eval( "\$som_name = $som_name;");
			}
			
			if(stristr(".swf",$som_image) || $som_name=="" || $som_name==" " ||$som_name=="&nbsp;" ||$som_name=="&amp;nbsp;") { 
				$no_category_text[$som_groupmenu]=1;
			}
			else {
				if($som_lien<>"") {
						
					if(strpos($som_lien,"LANG:_")===0) {
						$som_lien = str_replace("LANG:","",$som_lien);
						eval( "\$som_lien = $som_lien;");
					}
					$testepopup=strpos($som_lien,"javascript:window.open(");
					if($testepopup===0) {
						$som_lien = str_replace("window.open","menu_over_popup",$som_lien);
						$content.="<a href=\"$som_lien\"";
					}
					else {
						$content.="<a href=\"$som_lien\"";
						$testehttp=strpos($som_lien,"http://");
						$testeftp=strpos($som_lien,"ftp://");
						$testehttps=strpos($som_lien,"https://");
						if($testehttp===0 || $testeftp===0 ||$testehttps===0) {
							$content.=" target=\"_tab\"";
						}
					}
				$content.=" class=\"$categoryclass[$classpointeur]\">";
				}
				
				$content.="<span class=\"$categoryclass[$classpointeur]\">";
				
				$bold1 = ($som_bold=="on") ? "<strong>" : "" ;
				$bold2 = ($som_bold=="on") ? "</strong>" : "" ;
				$new = ($som_new=="on") ? "<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">" : "" ;
				
				$content.="".$bold1."$som_name".$bold2."".$new."";
			}
			
			$content.="</span>";
			
			if($som_lien<>"") {
				$content.="</a>";
			}
			
			if($som_dynamic=='on' && $detectMozilla!=1 && isset($moduleinthisgroup[$som_groupmenu]['0'])) {
				$zeimage = ($som_listbox=="on") ? "null.gif" :"down.gif" ;
				$content.="<img align=\"bottom\" id=\"menuupdown-$som_groupmenu\" src=\"$path_icon/admin/$zeimage\" border=0 alt=\"Show/Hide content\">";
			}
			if($som_center=="on") {
				$content.="</div>";
			}
			
			if($div==1) {
				$content.="</td><td style=\"vertical-align: top;\">";
			}
			elseif($horizontal==1) {
				$content.="</td></tr>\n";
			}
			else {
				$content.="</td></tr>\n";
			}
			
		}
		$keyinthisgroup=0;
		
		if($som_groupmenu!=99 && !isset($moduleinthisgroup[$som_groupmenu]['0'])) { 
			if($horizontal==1) {
				$content.="</table></td><td width=\"4\" bgcolor=\"$som_bgcolor\"></td>";
			}
			else {
				$content.="<tr bgcolor=\"$som_bgcolor\"><td height=\"4\"></td></tr>";
			}
		}
		elseif($som_groupmenu!=99 && isset($moduleinthisgroup[$som_groupmenu]['0'])) {
		if($som_listbox=="on") {
			$content.="<tr><td bgcolor=\"$som_bgcolor\"><span id=\"menu-$som_groupmenu\"></span>";
			$aenlever="menu_showhide\('menu-".$som_groupmenu."','nok','menuupdown-".$som_groupmenu."'\);";
			$total_actions = str_replace("$aenlever", "" , $total_actions);
			
			$content.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"menunowrap\"><tr><td width=\"100%\">";
			
			$content.="<form action=\"modules.php\" method=\"get\" name=\"menuformlistbox\">"
					."<select name=\"somlistbox$key\" onchange=\"menu_listbox(this.options[this.selectedIndex].value)\">"
					."<option value=\"select\">"._MENU_SELECTALINK."";
		}
		else {
			
			if($div==1) {
				if(!$som_bgcolor) {
					$divbgcolor=(!$bgcolor1) ? "#ffffff" : $bgcolor1;
				}
				else {
					$divbgcolor=$som_bgcolor;
				}
				
				$content.="<table id=\"menu-$som_groupmenu\" style=\"position: absolute; z-index: 2; background-color:".$divbgcolor."; border: 1px solid ".$bgcolor2.";\"><tr><td>";
			}
			else {
				$content.="<tr id=\"menu-$som_groupmenu\"><td bgcolor=\"$som_bgcolor\" width=\"100\">";
			}
			$content.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"menunowrap\">";
		}
		
		if($som_image<>"noimg" && !stristr(".swf",$som_image) && $som_center<>"on") { 
			$catimagesize = getimagesize("$path_icon/$som_image");
		}
		else {
			$catimagesize[0]=1; 
		}
		
		while ($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]) { 
			
			if(strpos($daysinthisgroup[$som_groupmenu][$keyinthisgroup],'8')!==false || $now<$date_debutinthisgroup[$som_groupmenu][$keyinthisgroup] || ($date_fininthisgroup[$som_groupmenu][$keyinthisgroup]>0 && $now>$date_fininthisgroup[$som_groupmenu][$keyinthisgroup])) {
				$keyinthisgroup++;
				continue;
			}
			
			if($grasinthisgroup[$som_groupmenu][$keyinthisgroup]=="on") { 
				$gras1="<strong>";
				$gras2="</strong>";
			}
			else {
				$gras1 = $gras2 = "";
			}
			
			if($som_listbox=="on") { 
				if($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]=="External Link") {
					 
					if(strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"LANG:_")===0) {
						$zelink_lang = str_replace("LANG:","",$linkinthisgroup[$som_groupmenu][$keyinthisgroup]);
						eval( "\$zelink_lang = $zelink_lang;");
						$linkinthisgroup[$som_groupmenu][$keyinthisgroup] = $zelink_lang;
					}
					$testehttp=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"http://");
					$testeftp=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"ftp://");
					$testehttps=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"https://");
					$testepopup=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"javascript:window.open(");
					if($testehttp===0 || $testeftp===0 || $testehttps===0) {
						$zelink= "_menu_targetblank";
					}
					elseif($testepopup===0) {
						$zelink=" target=\"popup_menu\"";
					}
					else {
						$zelink="";
					}
					
					$linklang=$linktextinthisgroup[$som_groupmenu][$keyinthisgroup];
					if(strpos($linklang,"LANG:_")===0) {
						$linklang = str_replace("LANG:","",$linklang);
						eval( "\$linklang = $linklang;");
						if($linklang=="") {$keyinthisgroup++;continue;} 
						$linktextinthisgroup[$som_groupmenu][$keyinthisgroup]=$linklang;
					}
					$content.= "<option value=\"".$linkinthisgroup[$som_groupmenu][$keyinthisgroup]."".$zelink."\">".$linktextinthisgroup[$som_groupmenu][$keyinthisgroup]."";
				}
				elseif($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]!="Horizonatal Rule" && $moduleinthisgroup[$som_groupmenu][$keyinthisgroup]!="MENUTEXTONLY" ) {
					if($poster_moduleinthisgroup[$som_groupmenu][$keyinthisgroup]!=2 || $is_admin==1) {
						$content.="<option value=\"".$urldumoduleinthisgroup[$som_groupmenu][$keyinthisgroup]."\">".$customtitle2inthisgroup[$som_groupmenu][$keyinthisgroup]."";
					}
				}
			}
			elseif($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]=="MENUTEXTONLY" || ($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]=="External Link" && !stristr("^modules.php\?name=", $linkinthisgroup[$som_groupmenu][$keyinthisgroup]) && !stristr("^((http(s)?)|(ftp(s)?))://".$_SERVER['SERVER_NAME']."/modules.php\?name=",$linkinthisgroup[$som_groupmenu][$keyinthisgroup]))) { 
				if(strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"LANG:_")===0) {
					$zelink_lang = str_replace("LANG:","",$linkinthisgroup[$som_groupmenu][$keyinthisgroup]);
					eval( "\$zelink_lang = $zelink_lang;");
					$linkinthisgroup[$som_groupmenu][$keyinthisgroup] = $zelink_lang;
				}
	
				$testepopup=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"javascript:window.open(");
				if($testepopup===0) {
							$linkinthisgroup[$som_groupmenu][$keyinthisgroup] = str_replace("window.open","menu_over_popup",$linkinthisgroup[$som_groupmenu][$keyinthisgroup]);
							$zelink="";
							}
				else {
					$testehttp=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"http://");
					$testeftp=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"ftp://");
					$testehttps=strpos($linkinthisgroup[$som_groupmenu][$keyinthisgroup],"https://");
					
					if($testehttp===0 || $testeftp===0 || $testehttps===0) {
						$zelink= " target=\"_tab\"";
					}
					else {
						$zelink="";
					}
				}
			
			$linklang=$linktextinthisgroup[$som_groupmenu][$keyinthisgroup];
			if(strpos($linklang,"LANG:_")===0) {
				$linklang = str_replace("LANG:","",$linklang);
				eval( "\$linklang = $linklang;");
				if($linklang=="") {$keyinthisgroup++;continue;} 
				$linktextinthisgroup[$som_groupmenu][$keyinthisgroup]=$linklang;
			}
			

				//sublevels
				if($keyinthisgroup==0) {
					$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]=0;
					$current_sublevel=0;
				}
				if($sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]>$current_sublevel) {
					if($imageinthisgroup[$som_groupmenu][$keyinthisgroup-1]=='tree-T.png') {
						$zebar="background: url($path_icon/categories/bar.gif) right top repeat-y;";
					}
					else {
						$zebar="";
					}
					$catimagesize[0]=0;
					if($div==1) {
						$sublevelzindex=$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]+2;
						$content.="<td style=\"vertical-align: top;\"><table id=\"".$id_sublevel."\" cellpadding=0 cellspacing=0 border=0 class=\"menunowrap\" style=\"position: absolute; z-index: ".$sublevelzindex."; border: 1px solid ".$bgcolor2."; background-color: ".$bgcolor1.";\">";
					}
					else {
					$content.="<tr id=\"".$id_sublevel."\"><td style=\"align: right;".$zebar."\"></td><td><table cellpadding=0 cellspacing=0 border=0 class=\"menunowrap\">";
					}
					$id_sublevel="";
					$id_sublevel_img="";
					$current_sublevel++;
				}
				
				//sublevels - showhide
				if($keyinthisgroup<count($moduleinthisgroup[$som_groupmenu])-1 && $sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]<$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]) {
					$ligne=($som_dynamic=='on') ? "<tr style=\"cursor: pointer;\" onclick=\"menu_showhide('menusublevel-$som_groupmenu-".($keyinthisgroup+1)."','ok','menuupdown-sublevel-$som_groupmenu-".($keyinthisgroup+1)."');\">" : "<tr>"; // onclick=\"menu_showhide('menusublevel-$som_groupmenu-$keyinthisgroup','ok','menuupdown-sublevel-$som_groupmenu-$keyinthisgroup');\"
					$id_sublevel="menusublevel-$som_groupmenu-".($keyinthisgroup+1);
					$id_sublevel_img="menuupdown-sublevel-$som_groupmenu-".($keyinthisgroup+1);
					$ferme_sublevels.= ($som_dynamic=='on') ? "menu_showhide('$id_sublevel','nok','$id_sublevel_img');" :  "" ;
					$sublevel_updownimg=($som_dynamic=='on') ? "<img id=\"".$id_sublevel_img."\" src=\"$path_icon/admin/up.gif\" alt=\"Show/Hide content\" border=0>" : "";
				}
				else {
					$ligne="<tr>";
					$sublevel_updownimg="";
				}
				
			
			$new = ($newinthisgroup[$som_groupmenu][$keyinthisgroup]=="on") ? "<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">" : "" ;
			$imagedulien="<img align=\"texttop\" src =\"$path_icon/categories/".$imageinthisgroup[$som_groupmenu][$keyinthisgroup]."\" border=0 alt=\"".$imageinthisgroup[$som_groupmenu][$keyinthisgroup]."\">";
			if($linkinthisgroup[$som_groupmenu][$keyinthisgroup]) { // v212b4 : n'affiche aucun lien si la case LIEN est vide.
				$lelien="<a href=\"".$linkinthisgroup[$som_groupmenu][$keyinthisgroup]."\"".$zelink." class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\">";
				$close_lelien="</a>";
			}
			else {
				$lelien="";
				$close_lelien="";
			}
			$letexte="<span class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\">".$linktextinthisgroup[$som_groupmenu][$keyinthisgroup]."</span>";
			
				if($imageinthisgroup[$som_groupmenu][$keyinthisgroup]<>"middot.gif" && ($linktextinthisgroup[$som_groupmenu][$keyinthisgroup]=="" || $linktextinthisgroup[$som_groupmenu][$keyinthisgroup]==" " || $linktextinthisgroup[$som_groupmenu][$keyinthisgroup]=="&nbsp;" || $linktextinthisgroup[$som_groupmenu][$keyinthisgroup]=="&amp;nbsp;")) { //si le texte du lien est vide l'image va �tre clickable
					$content.=$ligne."<td colspan=2 width=\"100%\">".$lelien.$imagedulien.$close_lelien.$new.""; //v2.1.2b4 : ajout de la variable $close_lelien
					$content.=$sublevel_updownimg."</td></tr>\n";
				}
				elseif($imageinthisgroup[$som_groupmenu][$keyinthisgroup]<>"middot.gif") { //si le texte n'est pas vide
					if($no_category_text[$som_groupmenu]===1) {	//V2.1.2beta3
						$content.=$ligne."<td colspan=2 align=\"left\" width=\"100%\">".$imagedulien."&nbsp;".$lelien.$gras1.$letexte.$gras2.$close_lelien.$new.""; //v2.1.2beta4 : ajout de $close_lelien
					}
					else {
						$content.=$ligne."<td width=\"$catimagesize[0]\" align=\"right\">".$imagedulien."</td><td>&nbsp;".$lelien.$gras1.$letexte.$gras2.$close_lelien.$new.""; //v2.1.2beta4 : ajout de $close_lelien
					}
					$content.=$sublevel_updownimg."</td></tr>\n";
				}
				else { // si l'image utilis�e est le middot
					if($no_category_text[$som_groupmenu]===1) {	//V2.1.2beta3
					// v2.1.2beta7 : ajout de la classe pour le middot
						$content.=$ligne."<td colspan=2 align=\"left\" width=\"100%\"><span class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\"><strong><big>&middot;</big></strong></span>&nbsp;".$lelien.$gras1.$letexte.$gras2.$close_lelien.$new.""; //v2.1.2beta4 : ajout de $close_lelien
					}
					else {
						$content.=$ligne."<td width=\"$catimagesize[0]\" align=\"right\"><span class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\"><strong><big>&middot;</big></strong></span></td><td>&nbsp;".$lelien.$gras1.$letexte.$gras2.$close_lelien.$new.""; //v2.1.2beta4 : ajout de $close_lelien
					}
					$content.=$sublevel_updownimg."</td></tr>\n";
				}
			
				
				//sublevels - ferme
				if($keyinthisgroup==count($moduleinthisgroup[$som_groupmenu])-1) {//on referme tous les sublevels, car on est au dernier lien de la cat�gorie
					for($sub=0;$sub<$current_sublevel;$sub++) {
						$content.="</table></td></tr>";
					}
				}
				elseif($current_sublevel>$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]) {
					for($sub=0;$sub<($current_sublevel-$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]);$sub++) {
						$content.="</table></td></tr>";
					}
					$current_sublevel=$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1];
				}
			
			}
			elseif($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]=="Horizonatal Rule") {
				$content.="<tr><td colspan=2>";
				$content.="<hr>";
				$content.="</td></tr>\n";
			}
			else {// un module normal, ou bien un lien interne (lien externe vers une page sp�cifique d'un module du site)
				if($moduleinthisgroup[$som_groupmenu][$keyinthisgroup]=="External Link") { //si c'est un lien externe, il commence par 'modules.php?name=' ==>c'est un lien vers un module du site
					$temponomdumodule=split("&", $linkinthisgroup[$som_groupmenu][$keyinthisgroup]);
					//v2.5
					$nomdumodule=$nomdumoduleinthisgroup[$som_groupmenu][$keyinthisgroup];
					$targetblank=$targetblankinthisgroup[$som_groupmenu][$keyinthisgroup];
					$customtitle2=$customtitle2inthisgroup[$som_groupmenu][$keyinthisgroup];
					$urldumodule=$urldumoduleinthisgroup[$som_groupmenu][$keyinthisgroup];

					// gestion multilingue : si le lien commence par 'LANG:_' alors c'est multilingue, donc on va afficher ce qui a �t� inscrit dans le fichier de langue.
					if(strpos($urldumodule,"LANG:_")===0) {
						$zelink_lang = str_replace("LANG:","",$urldumodule);
						eval( "\$zelink_lang = $zelink_lang;");
						$urldumodule = $zelink_lang;
					}//fin gestion multilingue
					// gestion multilingue : si le texte du lien commence par 'LANG:_' alors c'est multilingue, donc on va afficher ce qui a �t� inscrit dans le fichier de langue.
					$linklang=$customtitle2;
					if(strpos($linklang,"LANG:_")===0) {
						$linklang = str_replace("LANG:","",$linklang);
						eval( "\$linklang = $linklang;");
						if($linklang=="") {$keyinthisgroup++;continue;} //2.1.2beta7 : permet de ne pas afficher la ligne si le texte du lien n'a pas �t� d�fini pour cette langue.
						$customtitle2=$linklang;
					}//fin gestion multilingue
				}
				else {
					$temponomdumodule=array(); //beta8 : on vide cette variable car il n'y a aucun param�tre dans l'url.
					//v2.5
					$nomdumodule=$nomdumoduleinthisgroup[$som_groupmenu][$keyinthisgroup];
					$targetblank=$targetblankinthisgroup[$som_groupmenu][$keyinthisgroup];
					$customtitle2=$customtitle2inthisgroup[$som_groupmenu][$keyinthisgroup];
					$urldumodule=$urldumoduleinthisgroup[$som_groupmenu][$keyinthisgroup];
				}
				//v2.5
				if($som_dynamic=='on' && $detectMozilla!=1) {
					//d�tection am�lior�e de la cat�gorie � ouvrir
					$temprequesturi=split('&',$_SERVER['REQUEST_URI']);
					$tempurldumodule=split('&',$urldumodule);
					$nbparam=count($tempurldumodule);
					$nbrequest=count($temprequesturi);
					$requesturi=$temprequesturi[0];
					if($nbparam<=$nbrequest) {
						for ($i=1;$i<$nbparam;$i++) {
							$requesturi.="&".$temprequesturi[$i];
						}
					}
					if(strstr(addcslashes("$urldumodule$", '?&'), $requesturi)) { // si la page visualis�e est le module[$z], alors on r�cup�re son groupmenu pour ne pas enrouler la cat�gorie par d�faut.
						$categorieouverte=$som_groupmenu;
						$keyouvert=$keyinthisgroup;
					}
				}
				if($imageinthisgroup[$som_groupmenu][$keyinthisgroup]!="middot.gif") {
					$limage="<img align=\"texttop\" src =\"$path_icon/categories/".$imageinthisgroup[$som_groupmenu][$keyinthisgroup]."\" border=\"0\" alt=\"".$imageinthisgroup[$som_groupmenu][$keyinthisgroup]."\">";
				}
				else {
					$limage="<strong><big>&middot;</big></strong>";
				}

				//v2.5
				if($poster_moduleinthisgroup[$som_groupmenu][$keyinthisgroup]==2) {
					$limage="<img align=\"texttop\" src =\"$path_icon/admin/interdit.gif\" title=\"".$whyrestricted[$som_groupmenu][$keyinthisgroup]."\" alt=\"".$whyrestricted[$som_groupmenu][$keyinthisgroup]."\">";
				}

				if(($newpms[0]) AND ($nomdumodule =="Private_Messages")) {
					$disp_pmicon="<img align=\"texttop\" src =\"images/blocks/email-y.gif\" height=\"10\" width=\"14\" alt=\""._MENU_NEWPM."\" title=\""._MENU_NEWPM."\">";
				}
				else {
					$disp_pmicon="";
				}
				////// ajout support NEW! automatique pour les modules de base.
				$new = ($newinthisgroup[$som_groupmenu][$keyinthisgroup]=="on") ? "<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">" : "" ;

				if($nomdumodule=="Downloads" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") {
					$where = (strstr("^cid=[0-9]*$",$temponomdumodule[2])) ? " WHERE $temponomdumodule[2]" : "";
					$sqlimgnew="SELECT date FROM ".$prefix."_downloads_downloads".$where." order by date desc limit 1";
					$resultimgnew=$db->sql_query($sqlimgnew);
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
					if($rowimgnew['date']) {
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['date'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				elseif($nomdumodule=="Web_Links" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") {
					$where = (strstr("^cid=[0-9]*$",$temponomdumodule[2])) ? " WHERE $temponomdumodule[2]" : "";
					$sqlimgnew="SELECT date FROM ".$prefix."_links_links".$where." order by date desc limit 1";
					$resultimgnew=$db->sql_query($sqlimgnew);
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
					if($rowimgnew['date']) {
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['date'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				elseif($nomdumodule=="Content" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") {
					$where = (strstr("^cid=[0-9]*$",$temponomdumodule[2])) ? " WHERE $temponomdumodule[2]" : "";
					$sqlimgnew="SELECT date FROM ".$prefix."_pages".$where." order by date desc limit 1";
					$resultimgnew=$db->sql_query($sqlimgnew);
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
					if($rowimgnew['date']) {
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['date'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				elseif($nomdumodule=="Reviews" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") {
					$where = "";
					$sqlimgnew="SELECT date FROM ".$prefix."_reviews".$where." order by date desc limit 1";
					$resultimgnew=$db->sql_query($sqlimgnew);
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
					if($rowimgnew['date']) {
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $rowimgnew['date'], $datetime);
						$zedate = mktime(0,0,0,$datetime[2],$datetime[3],$datetime[1]);
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				else // Music module
				if($nomdumodule=="Music" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") 
				{
				    global $db3, $musicprefix;
					
					$where = (strstr("^new_topic=[0-9]*$",$temponomdumodule[1])) ? " WHERE ".str_replace("new_","",$temponomdumodule[1])."" : "";
				
					$sqlimgnew="SELECT time FROM ".$musicprefix."_song_posts".$where." order by time desc limit 1";
				
					$resultimgnew=$db3->sql_query($sqlimgnew);
				
					$rowimgnew = $db3->sql_fetchrow($resultimgnew);
				
					if($rowimgnew['time']) 
					{
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['time'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
					
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				else //News module
				if($nomdumodule=="Blog" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") 
				{
				    global $db, $prefix;
					
					$where = (strstr("^new_topic=[0-9]*$",$temponomdumodule[1])) ? " WHERE ".str_replace("new_","",$temponomdumodule[1])."" : "";
				
					$sqlimgnew="SELECT datePublished FROM ".$prefix."_stories".$where." order by datePublished desc limit 1";
				
					$resultimgnew=$db->sql_query($sqlimgnew);
				
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
				
					if($rowimgnew['datePublished']) 
					{
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['time'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
					
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}
				else // Blogs module
				if($nomdumodule=="Blogs" && $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]!="-1") {
					$where = (strstr("^new_topic=[0-9]*$",$temponomdumodule[1])) ? " WHERE ".str_replace("new_","",$temponomdumodule[1])."" : "";
					$sqlimgnew="SELECT datePublished FROM ".$prefix."_blogs".$where." order by datePublished desc limit 1";
					$resultimgnew=$db->sql_query($sqlimgnew);
					$rowimgnew = $db->sql_fetchrow($resultimgnew);
					if($rowimgnew['datePublished']) {
						strstr ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $rowimgnew['datePublished'], $datetime);
						$zedate = mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
						//$now=time();
						if(intval(($now-$zedate)/86400) <= $newdaysinthisgroup[$som_groupmenu][$keyinthisgroup]) {
							$new="<img align=\"texttop\" src =\"$path_icon/admin/$imgnew\" border=0 title=\""._MENU_NEWCONTENT."\" alt=\""._MENU_NEWCONTENT."\">";
						}
					}
				}

				//sublevels - ouvre
				if($keyinthisgroup==0) {
					$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]=0;
					$current_sublevel=0;
				}
				if($sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]>$current_sublevel) {
					if($imageinthisgroup[$som_groupmenu][$keyinthisgroup-1]=='tree-T.png') {
						$zebar="background: url($path_icon/categories/bar.gif) right top repeat-y;";
					}
					else {
						$zebar="";
					}
					$catimagesize[0]=0;
					if($div==1) {
						$sublevelzindex=$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]+2;
						$content.="<td style=\"vertical-align: top;\"><table id=\"".$id_sublevel."\" cellpadding=0 cellspacing=0 border=0 class=\"menunowrap\" style=\"position: absolute; z-index: ".$sublevelzindex."; border: 1px solid ".$bgcolor2."; background-color: ".$bgcolor1.";\">";
					}
					else {
					$content.="<tr id=\"".$id_sublevel."\"><td style=\"align: right;".$zebar."\"></td><td><table cellpadding=0 cellspacing=0 border=0 class=\"menunowrap\">";
					}
					$id_sublevel="";
					$id_sublevel_img="";
					$current_sublevel++;
				}
				//sublevels - showhide
				if($keyinthisgroup<count($moduleinthisgroup[$som_groupmenu])-1 && $sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]<$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]) {
					$ligne=($som_dynamic=='on') ? "<tr style=\"cursor: pointer;\" onclick=\"menu_showhide('menusublevel-$som_groupmenu-".($keyinthisgroup+1)."','ok','menuupdown-sublevel-$som_groupmenu-".($keyinthisgroup+1)."');\">" : "<tr>"; // onclick=\"menu_showhide('menusublevel-$som_groupmenu-$keyinthisgroup','ok','menuupdown-sublevel-$som_groupmenu-$keyinthisgroup');\"
					$id_sublevel="menusublevel-$som_groupmenu-".($keyinthisgroup+1);
					$id_sublevel_img="menuupdown-sublevel-$som_groupmenu-".($keyinthisgroup+1);
					$ferme_sublevels.= ($som_dynamic=='on') ? "menu_showhide('$id_sublevel','nok','$id_sublevel_img');" : "" ;
					$sublevel_updownimg=($som_dynamic=='on') ? "<img id=\"".$id_sublevel_img."\" src=\"$path_icon/admin/up.gif\" alt=\"Show/Hide content\" border=0>" : "";
				}
				else {
					$ligne="<tr>";
					$sublevel_updownimg="";
				}

				if($limage!="middot.gif" && ($customtitle2=="" || $customtitle2==" " || $customtitle2=="&nbsp;" || $customtitle2=="&amp;nbsp;")) { //si le texte du lien est vide l'image va �tre clickable
					if($no_category_text[$som_groupmenu]===1) {	//V2.1.2beta3
						$content.=$ligne."<td colspan=2 align=\"left\" width=\"100%\">&nbsp;<a href=\"".$urldumodule."\" ".$targetblank.">".$limage."</a>".$new."";
					}
					else {
						$content.=$ligne."<td width=\"$catimagesize[0]\" align=\"right\"></td><td>&nbsp;<a href=\"".$urldumodule."\" ".$targetblank.">".$limage."</a>".$new."";
					}
					$content.=$sublevel_updownimg."</td>";
					
					if(($div==1) && ($keyinthisgroup<count($moduleinthisgroup[$som_groupmenu])-1 && $sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]<$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1])) {
						//si ce lien est le parent d'un sublevel, et qu'on utilise les layers il ne faut pas fermer la ligne.
					}
					else {
						$content.="</tr>\n";
					}
				}
				else {
					$width=" width=\"$catimagesize[0]\"";
					if($no_category_text[$som_groupmenu]===1) {	//V2.1.2beta3
						$content.=$ligne."<td colspan=2 align=\"left\" width=\"100%\">".$limage."".$disp_pmicon."";
					}
					else {
						$content.=$ligne."<td".$width." align=\"right\">".$limage.""."</td><td>".$disp_pmicon."";
					}
					$content.="&nbsp;<a href=\"".$urldumodule."\" class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\" ".$targetblank."><span class=\"".$classinthisgroup[$som_groupmenu][$keyinthisgroup]."\">".$gras1."$customtitle2".$gras2."</span></a>".$new."";
					$content.=$sublevel_updownimg."</td>";
					if(($div==1) && ($keyinthisgroup<count($moduleinthisgroup[$som_groupmenu])-1 && $sublevelinthisgroup[$som_groupmenu][$keyinthisgroup]<$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1])) {
						//si ce lien est le parent d'un sublevel, et qu'on utilise les layers il ne faut pas fermer la ligne.
					}
					else {
					$content.="</tr>\n";
					}
				}

				//sublevels - ferme
				if($keyinthisgroup==count($moduleinthisgroup[$som_groupmenu])-1) {//on referme tous les sublevels, car on est au dernier lien de la cat�gorie
					for($sub=0;$sub<$current_sublevel;$sub++) {
						$content.="</table></td></tr>";
					}
				}
				elseif($current_sublevel>$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]) {
					for($sub=0;$sub<($current_sublevel-$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1]);$sub++) {
						$content.="</table></td></tr>";
					}
					$current_sublevel=$sublevelinthisgroup[$som_groupmenu][$keyinthisgroup+1];
				}
	   		}// end else (pas lien externe et pas listbox)
			$keyinthisgroup++;
		}// end while
		if($som_listbox=="on") {
			$content.="</select></form></td></tr>";
		}
		$content.="</table>";
		
		if($div==1) {
			$content.="</td></tr></table>";
			$content.="</td></tr>";
			
		}
		else {
			$content.="</td></tr>";
		}
			
		if($horizontal==1) {
			$content.="</table></td><td width=\"4\" bgcolor=\"$som_bgcolor\"></td>";
		}
		else {
			$content.="<tr bgcolor=\"$som_bgcolor\"><td height=\"4\"></td></tr>";
		}
		}//end if somgroupmenu<>99
	
		if($som_groupmenu == 99 && $is_admin==1 && $horizontal!=1) { // si on est � la cat�gorie 99, on affiche aux admins tous les modules install�s/activ�s/visibles qui n'ont pas �t� affich�s dans les cat�gories.
			if($som_name!="menunoadmindisplay") {
				$showadmin=1;
				$content.="<tr><td>";
				for ($z=0;$z<count($module);$z++) {
					$customtitle2 = str_replace ("_"," ", $module[$z]);
					if($customtitle[$z] != "") {
						$customtitle2 = $customtitle[$z];
					}
					if($module[$z] != $main_module) {
						if(($is_admin===1 AND $view[$z] == 2) OR $view[$z] != 2) {
							$incategories=0;
							for ($i=0;$i<count($totalcategorymodules);$i++) {
								if($module[$z]==$totalcategorymodules[$i]) {
									$incategories=1;
								}
							}
							if($incategories==0) {
								$flagmenu = $flagmenu+1;
								if($flagmenu==1) {
									$content .="<hr><div align=\"center\">"._MENU_ADMINVIEWALLMODULES."</div><br>";   // si il y a des modules affich�s en rubrique 99, on affiche avant une ligne horizontale
								}
								$urldumodule99 = ($gt_url[$z]!="") ? $gt_url[$z] : "modules.php?name=".$module[$z] ; // GT-NextGen
								if(($newpms[0]) AND ($module[$z]=="Private_Messages")) { // si PMs non lus, on affiche le logo mail
									$content .= "<strong><big>&middot;</big></strong><img align=\"texttop\" src =\"images/blocks/email-y.gif\" height=\"10\" width=\"14\" alt=\""._MENU_NEWPM."\" title=\""._MENU_NEWPM."\"><a href=\"".$urldumodule99."\">$customtitle2</a><br>\n";
								}
								else {
									$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$urldumodule99."\">$customtitle2</a><br>\n";
								}
							}
						}
					}
				}//end for groupmenu=99
				$content.="</td></tr>";
			}
			else {
				$showadmin=0;
			}
		}//end if groupmenu=99
	}
	$content.="</table>";
	if($general_dynamic==1 && $detectMozilla!=1) { // on va r�enrouler toutes les cat�gories, sauf celle contenant le module affich� sur la page
		if(isset($categorieouverte)) {
			$aenlever="menu_showhide\('menu-".$categorieouverte."','nok','menuupdown-".$categorieouverte."'\);";
			$total_actions = str_replace("$aenlever", "" , $total_actions);
		}
		if(isset($keyouvert)) { // on ne r�enroule pas les sublevels qui vont jusqu'au module affich� sur la page (on laisse l'arborescence du sublevel).
								 // note : normalement tous les cas d'arborescence sont pr�vus avec le code ci-dessous. donc normalement pas de bug ici
								 //        mais si c'est le cas, il faudra pr�voir qq tasses de caf�, et un code BEAUCOUP plus long :-/
			$aenlever_sublevels="menu_showhide\('menusublevel-".$categorieouverte."-".$keyouvert."','nok','menuupdown-sublevel-".$categorieouverte."-".$keyouvert."'\);";
			$ferme_sublevels = str_replace("$aenlever_sublevels", "" , $ferme_sublevels);
			$j=$keyouvert;
			for ($i=$keyouvert-1;$i>=0;$i--) {
				if($sublevelinthisgroup[$categorieouverte][$i]<=$sublevelinthisgroup[$categorieouverte][$j] && $sublevelinthisgroup[$categorieouverte][$i]<=$sublevelinthisgroup[$categorieouverte][$keyouvert]) {
					$aenlever_sublevels="menu_showhide\('menusublevel-".$categorieouverte."-".$i."','nok','menuupdown-sublevel-".$categorieouverte."-".$i."'\);";
					$ferme_sublevels = str_replace("$aenlever_sublevels", "" , $ferme_sublevels);
					$j--;
				}
			}
		}
		$content.="<script type=\"text/javascript\" language=\"JavaScript\">$total_actions;\n";
		$content.=$ferme_sublevels;
		$content.="</script>";
		// Note: j'utilise le jscript pour fermer les cat�gories (et sublevels) au d�part, au lieu de mettre "display: none" pour leur contenu.
		// C'est peut-�tre moins "�l�gant", mais le but est de faire fonctionner le menu sur des navigateurs SANS jscript (ou d�sactiv�).
		// (�a serait relativement g�nant de ne pas pouvoir naviguer dans le menu d'un site web si on n'a pas de jscript !) ;-)
	}


    /* If you're Admin you and only you can see Inactive modules and test it */
    /* If you copied a new module is the /modules/ directory, it will be added to the database */

if( $showadmin==1 && $is_admin===1 && $horizontal!=1) {

	$key=count($module); // $key va permettre de se positionner dans $module[] pour rajouter des modules � la fin
	$content .= "<br><center><b>"._INVISIBLEMODULES."</b><br>";
	$content .= "<font class=\"tiny\">"._ACTIVEBUTNOTSEE."</font></center>";
	$content.="<form action=\"modules.php\" method=\"get\" name=\"menuformlistboxinvisibles\">"
	."<select name=\"somlistboxinvisibles\" onchange=\"menu_listbox(this.options[this.selectedIndex].value)\">"
	."<option value=\"select\">"._MENU_SELECTALINK."";
	$sql = "SELECT * FROM ".$prefix."_modules WHERE active='1' AND inmenu='0' ORDER BY title ASC";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$module[$key]=$row['title'];
		$mn_title = $row['title'];
		$custom_title = $row['custom_title'];
		$mn_title2 = (!$custom_title) ? str_replace("_", " ", $mn_title) : $custom_title;
		$urldumodule_admin = (isset($row['url'])) ? $row['url'] : "modules.php?name=".$mn_title ; // GT-NextGen
		$content .= "<option value=\"".$urldumodule_admin."\">".$mn_title2."";
		$key++;
	}
	$content.= "</select></form>\n";

	
	$content .= "<br><center><b>"._NOACTIVEMODULES."</b><br>";
	$content .= "<font class=\"tiny\">"._FORADMINTESTS."</font></center>";
	$content.="<form action=\"modules.php\" method=\"get\" name=\"menuformlistboxinactifs\">"
				."<select name=\"somlistboxinactifs\" onchange=\"menu_listbox(this.options[this.selectedIndex].value)\">"
				."<option value=\"select\">"._MENU_SELECTALINK."";
	
	$sql = "SELECT title, custom_title FROM ".$prefix."_modules WHERE active='0' ORDER BY title ASC";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$module[$key]=$row['title'];
		$key++;
		$mn_title = $row['title'];
		$custom_title = $row['custom_title'];
		$mn_title2 = (!$custom_title) ? str_replace("_", " ", $mn_title) : $custom_title;
		if($custom_title != "") {
			$mn_title2 = $custom_title;
		}
		$urldumodule_admin = (isset($row['url'])) ? $row['url'] : "modules.php?name=".$mn_title ; // GT-NextGen
		$content .= "<option value=\"".$urldumodule_admin."\">".$mn_title2."";
		$dummy = 1;
	}
	$content.= "</select></form>\n";

	
	
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
	    if( (!strstr("[.]",$file)) ) {
						// ajout d'un check pour diminuer le nombre de requets SQL : on ne checke QUE les modules qui ne sont pas 
			$trouve=0;  //dans  $module c'est � dire les modules qui ne sont pas "actifs" ET "visibles" (==> modules inactifs
			for ($i=0;$i<count($module);$i++) {
				if($module[$i]==$file) {
				$trouve=1;
				}
	    	}
			if($trouve<>1) {
				$modlist .= "$file ";
			}
		}
	}
	closedir($handle);
	$modlist = explode(" ", $modlist);
	sort($modlist);
	for ($i=0; $i < sizeof($modlist); $i++) {
	    if($modlist[$i] != "") {
			$sql = "SELECT mid FROM ".$prefix."_modules WHERE title='$modlist[$i]'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$mid = $row['mid'];
			if($mid == "") {
			    $db->sql_query("INSERT INTO ".$prefix."_modules (mid, title, custom_title, active, view, inmenu) VALUES (NULL, '$modlist[$i]', '$modlist[$i]', '0', '0', '1')");
			}
	    }
	}
}//end if admin


function menu_is_user($user, $managment_group) 
{
    global $network_prefix, $uid, $userpoints;

    if(!is_array($user)) 
	{
		$user = addslashes($user); 
        $user = base64_decode($user);
		$user = addslashes($user); 
        $user = explode(":", $user);
        $uid = "$user[0]";
        $pwd = "$user[2]";
    } 
	else 
	{
        $uid = "$user[0]";
        $pwd = "$user[2]";
    }
	
	$uid = addslashes($uid); 
	$uid=intval($uid); 
    
	if($uid != "" AND $pwd != "") 
	{
		if($managment_group==0) 
		{
        	$sql = "SELECT user_password FROM ".$network_prefix."_users WHERE user_id='$uid'";
		}
		else 
		if($managment_group==1) 
		{
			$sql = "SELECT user_password, points FROM ".$network_prefix."_users WHERE user_id='$uid'";
		}
		else 
		{
		  die("There Seems To Be A problem!!");
		}
        
		$result = $db->sql_query($sql);
        
		$row = $db->sql_fetchrow($result);
        
		$pass = $row['user_password'];
        
		if($pass == $pwd && $pass != "") 
		{
			$userpoints = ($managment_group==1) ? $row['points'] : "";
            return 1;
        }
    }
    return 0;
}


function menu_get_theme($is_user) 
{
    global $user, $cookie, $Default_Theme;

    if($is_user==1) 
	{
        $user2 = base64_decode($user);
    
	    $t_cookie = explode(":", $user2);
    
	    if($t_cookie[9]=="") $t_cookie[9]=$Default_Theme;
    
	    if(isset($theme)) $t_cookie[9]=$theme;
    
	    if(!$tfile=@opendir("themes/$t_cookie[9]")) 
		{
            $ThemeSel = $Default_Theme;
        } 
		else 
		{
            $ThemeSel = $t_cookie[9];
        }
    } 
	else 
	{
        $ThemeSel = $Default_Theme;
    }
    
	return($ThemeSel);
}
?>
