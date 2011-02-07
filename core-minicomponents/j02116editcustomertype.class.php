<?php
/**
 * Core file
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 4 
* @package Jomres
* @copyright	2005-2011 Vince Wooll
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly, however all images, css and javascript which are copyright Vince Wooll are not GPL licensed and are not freely distributable. 
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################

/**
#
 * Edit customer type
 #
* @package Jomres
#
 */
class j02116editcustomertype {
	/**
	#
	 * Constructor: Collects data for and displays the edit customer type page
	#
	 */
	function j02116editcustomertype()
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=true; return;
			}
		$defaultProperty=getDefaultProperty();
		$id       = jomresGetParam( $_REQUEST, 'id', 0 );
		$yesno = array();
		$yesno[] = jomresHTML::makeOption( '0', _JOMRES_COM_MR_NO );
		$yesno[] = jomresHTML::makeOption( '1', _JOMRES_COM_MR_YES );

		$posneg = array();
		$posneg[] = jomresHTML::makeOption( '0', "-" );
		$posneg[] = jomresHTML::makeOption( '1', "+" );

		$output['PAGETITLE']=jr_gettext('_JOMRES_CONFIG_VARIANCES_CUSTOMERTYPES',_JOMRES_CONFIG_VARIANCES_CUSTOMERTYPES);
		$output['HTYPE']=jr_gettext('_JOMRES_VARIANCES_TYPE',_JOMRES_VARIANCES_TYPE);
		//$output['HTYPE_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_TYPE_TT',_JOMRES_VARIANCES_TYPE_TT,false ) );
		$output['HTYPE_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_TYPE_TT',$hover_title="",_JOMRES_VARIANCES_TYPE,'_JOMRES_VARIANCES_TYPE_TT',$class="",$type="infoimage");
		$output['HNOTES']=jr_gettext('_JOMRES_VARIANCES_NOTES',_JOMRES_VARIANCES_NOTES);
		//$output['HNOTES_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_NOTES_TT',_JOMRES_VARIANCES_NOTES_TT,false ) );
		$output['HNOTES_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_NOTES_TT',$hover_title="",_JOMRES_VARIANCES_NOTES_TT,'_JOMRES_VARIANCES_NOTES_TT',$class="",$type="infoimage");
		$output['HMAXIMUM']=jr_gettext('_JOMRES_VARIANCES_MAXIMUM',_JOMRES_VARIANCES_MAXIMUM);
		//$output['HMAXIMUM_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_MAXIMUM_TT',_JOMRES_VARIANCES_MAXIMUM_TT,false ) );
		$output['HMAXIMUM_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_MAXIMUM_TT',$hover_title="",_JOMRES_VARIANCES_MAXIMUM_TT,'_JOMRES_VARIANCES_MAXIMUM_TT',$class="",$type="infoimage");
		$output['HISPERCENTAGE']=jr_gettext('_JOMRES_VARIANCES_ISPERCENTAGE',_JOMRES_VARIANCES_ISPERCENTAGE);
		//$output['HISPERCENTAGE_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_ISPERCENTAGE_TT',_JOMRES_VARIANCES_ISPERCENTAGE_TT, false) );
		$output['HISPERCENTAGE_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_ISPERCENTAGE',$hover_title="",_JOMRES_VARIANCES_ISPERCENTAGE_TT,'_JOMRES_VARIANCES_ISPERCENTAGE',$class="",$type="infoimage");
		$output['HPOSNEG']=jr_gettext('_JOMRES_VARIANCES_POSNEG',_JOMRES_VARIANCES_POSNEG);
		//$output['HPOSNEG_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_POSNEG_TT',_JOMRES_VARIANCES_POSNEG_TT,false ) );
		$output['HPOSNEG_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_POSNEG',$hover_title="",_JOMRES_VARIANCES_POSNEG,'_JOMRES_VARIANCES_POSNEG_TT',$class="",$type="infoimage");
		$output['HVARIANCE']=jr_gettext('_JOMRES_VARIANCES_VARIANCE',_JOMRES_VARIANCES_VARIANCE);
		//$output['HVARIANCE_TT']=jomresToolTips( jr_gettext('_JOMRES_VARIANCES_VARIANCE_TT',_JOMRES_VARIANCES_VARIANCE_TT,false ) );
		$output['HVARIANCE_TT']=jomres_makeTooltip('_JOMRES_VARIANCES_VARIANCE',$hover_title="",_JOMRES_VARIANCES_VARIANCE_TT,'_JOMRES_VARIANCES_VARIANCE',$class="",$type="infoimage");
		$output['ID']= $id;

		if ( $id!="" )
			{
			$query="SELECT `type`,`notes`,`maximum`,`is_percentage`,`posneg`,`variance`,`published` FROM `#__jomres_customertypes` WHERE id = '".(int)$id."' AND property_uid = '".(int)$defaultProperty."' ORDER BY type";
			$ex =doSelectSql($query,2);

			$output['TYPE']=stripslashes($ex['type']);
			$output['NOTES']=stripslashes($ex['notes']);
			$output['MAXIMUM']=$ex['maximum'];

			$output['ISPERCENTAGE']=jomresHTML::selectList( $yesno, 'is_percentage','class="inputbox" size="1"', 'value', 'text', $ex['is_percentage']);
			$output['POSNEG']=jomresHTML::selectList( $posneg, 'posneg','class="inputbox" size="1"', 'value', 'text', $ex['posneg']);
			$output['VARIANCE']=number_format($ex['variance'],2, '.', '');
			}
		else
			{
			$output['TYPE']="";
			$output['NOTES']="";
			$output['MAXIMUM']="10";
			$output['ISPERCENTAGE']=jomresHTML::selectList( $yesno, 'is_percentage','class="inputbox" size="1"', 'value', 'text', "0");
			$output['POSNEG']=jomresHTML::selectList( $posneg, 'posneg','class="inputbox" size="1"', 'value', 'text', "0");
			$output['VARIANCE']=number_format(0,2);
			}

		$output['JOMRESTOKEN'] ='<input type="hidden" name="jomrestoken" value="'.jomresSetToken().'"><input type="hidden" name="no_html" value="1"/>';

		$jrtbar =jomres_getSingleton('jomres_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('save','','',true,'saveCustomerType');
		$jrtb .= $jrtbar->toolbarItem('cancel',jomresURL(JOMRES_SITEPAGE_URL."&task=listCustomerTypes"),'');
		if ($id != 0 )
			$jrtb .= $jrtbar->toolbarItem('delete',jomresURL(JOMRES_SITEPAGE_URL."&task=deleteCustomerType".jomresURLToken()."&no_html=1&id=$id"),'');
		$jrtb .= $jrtbar->endTable();
		$output['JOMRESTOOLBAR']=$jrtb;

		$output['JOMRES_SITEPAGE_URL']=JOMRES_SITEPAGE_URL;
		
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot( JOMRES_TEMPLATEPATH_BACKEND );
		$tmpl->readTemplatesFromInput( 'edit_customertype.html' );
		$tmpl->addRows( 'pageoutput', $pageoutput );
		$tmpl->displayParsedTemplate();
		}

	function touch_template_language()
		{
		$output=array();

		$output[]		=jr_gettext('_JOMRES_CONFIG_VARIANCES_CUSTOMERTYPES',_JOMRES_CONFIG_VARIANCES_CUSTOMERTYPES);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_TYPE',_JOMRES_VARIANCES_TYPE);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_TYPE_TT',_JOMRES_VARIANCES_TYPE_TT);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_NOTES',_JOMRES_VARIANCES_NOTES);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_NOTES_TT',_JOMRES_VARIANCES_NOTES_TT);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_MAXIMUM',_JOMRES_VARIANCES_MAXIMUM);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_MAXIMUM_TT',_JOMRES_VARIANCES_MAXIMUM_TT);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_ISPERCENTAGE',_JOMRES_VARIANCES_ISPERCENTAGE);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_ISPERCENTAGE_TT',_JOMRES_VARIANCES_ISPERCENTAGE_TT);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_POSNEG',_JOMRES_VARIANCES_POSNEG);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_POSNEG_TT',_JOMRES_VARIANCES_POSNEG_TT);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_VARIANCE',_JOMRES_VARIANCES_VARIANCE);
		$output[]		=jr_gettext('_JOMRES_VARIANCES_VARIANCE_TT',_JOMRES_VARIANCES_VARIANCE_TT);

		foreach ($output as $o)
			{
			echo $o;
			echo "<br/>";
			}
		}
	/**
	#
	 * Must be included in every mini-component
	#
	 * Returns any settings the the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	#
	 */
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
?>