<?php
/*
Plugin Name: shopwelt.de-Widget 
Plugin URI: https://www.shopwelt.de/
Description: Diese Plugin erm&ouml;glicht das Einbinden des shopwelt.de-Widget 
Version: 1.01
Author: eVendi GmbH & Co. KG
Author URI: https://www.shopwelt.de/
*/
$ShopweltWidgetPluginVersion = '1.01';
function addShopweltWidgetToPost($content='') {
	try {
		if(preg_match_all('/\[evwidget[^\]]+?\]/i', $content, $evWidInfo)) {
			foreach ($evWidInfo[0] as $item) {
				preg_match_all('/evwidget=([^\]]*)\]/i', $item, $evkeyw);
				$evdiv = '<div rel="evwidget" evwig="' . $evkeyw[1][0] . '" style="width:100%;"></div>';
				$content = str_replace($item, $evdiv, $content);
				$evkeyw = null;
			}
		} 
	} catch (Exception $e) {
	}
	return $content;
}

function addShopweltWidgetFooter() {
	$Opt = ShopweltLoadOptions();  

    echo '<script type="text/javascript">';
	echo 'var _eVWig = { pubtag: \'' . $Opt['PubTag'] . 
			'\', type: \'diroffer\', font: \'' . $Opt['Font'] . 
			'\', fontsize: ' . $Opt['FSize'] . ', linkcolor: \'' . $Opt['CLnk'] . '\', spacerline: \'' . $Opt['CSpl'] . 
			'\', backcolor: \'' . $Opt['CBg'] . '\', number: ' . $Opt['OffCnt'] . ' };';
	echo '</script><script type="text/javascript" src="//data.shopwelt.de/v101/liveprice.js"></script><script type="text/javascript">';
	echo 'var _eVWiget = new _eVWigetLoader();_eVWiget.load();</script>';
}

function ShopweltLoadOptions() {
	return Array (
		'PubTag' => get_option('_ShopweltWidgetOption_PubTag', 'evwpwidem-de'),
		'Font' => get_option('_ShopweltWidgetOption_Font', 'Arial, Verdana'),
		'FSize' => get_option('_ShopweltWidgetOption_FontSize', '14'),
		'CSpl' => get_option('_ShopweltWidgetOption_CSpLine', '#CCCCCC'),
		'CLnk' => get_option('_ShopweltWidgetOption_CLnk', '#000000'),
		'CBg' => get_option('_ShopweltWidgetOption_CBg', '#FFFFFF'),
		'OffCnt' => get_option('_ShopweltWidgetOption_OfferCnt', '7')
	);  
}

function ShopweltAdminOptions() {
	if(!current_user_can('manage_options')){wp_die( __('You do not have sufficient permissions to access this page.') );}
	$hidden_field_name='ShopweltWigUpdate';
	
	$Opt = ShopweltLoadOptions();
	
	if(isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name]=='Y'){
		$Opt['PubTag'] = $_POST['evPubTag'];
		$Opt['Font'] = $_POST['evFont'];
		$Opt['FSize'] = $_POST['evFSize'];
		$Opt['CSpl'] = $_POST['evCSpl'];
		$Opt['CBg'] = $_POST['evCBg'];
		$Opt['CLnk'] = $_POST['evCLnk'];
		
		$Opt['OffCnt'] = $_POST['evOffCnt'];
		update_option('_ShopweltWidgetOption_PubTag', $Opt['PubTag']);
		update_option('_ShopweltWidgetOption_Font', $Opt['Font']);
		update_option('_ShopweltWidgetOption_FontSize', $Opt['FSize']);
		update_option('_ShopweltWidgetOption_CSpLine', $Opt['CSpl']);
		update_option('_ShopweltWidgetOption_CBg', $Opt['CBg']);
		update_option('_ShopweltWidgetOption_CLnk', $Opt['CLnk']);
		update_option('_ShopweltWidgetOption_OfferCnt', $Opt['OffCnt']);
		?><div class="updated"><p><strong>Einstellungen gespeichert</strong></p></div><?php }
	?><div class="wrap" id="poststuff">
	<style>
		.evcolorboxdefault {
			display:inline-block; width:20px; margin-left:3px; border: 1px solid #CCC; height:20px; vertical-align: top;
		}
	</style>
	<h2>shopwelt.de WordPress Plugin (Version <?php  echo $GLOBALS['ShopweltWidgetPluginVersion']; ?>)</h2>
		<?php 
		if(strlen($Opt['PubTag']) == 0) { 
			?><p style="color:#990000;">Um dieses Plugin nutzen zu k&ouml;nnen muss der "PubTag" ausgef&uuml;llt sein.</p><?php } 
		?>
		<div class="postbox">
		<h3 class="hndle"><span>Grundeinstellungen</span></h3>
		<div class="inside">
		<form name="form1" method="post" action="" style="margin:0px">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
			<p><table cellpadding="0" cellspacing="5" border="0" style="table-layout:fixed;">
				<tr>
					<td width="220"><b>Partner Tag:</b></td>
					<td width="220"><input type="text" name="evPubTag" value="<?php echo $Opt['PubTag']; ?>" size="30"></td>
					<td>Der „PublisherTag“ (PubTag) ist für die Abrechnung und die Zuordnung der generierten Klicks/Weiterleitungen notwendig. Diesen erhalten Sie <u>nach</u> erfolgreicher Registrierung bei shopwelt.de. Für die Dauer des kostenfreien Testzeitraums ist eine Demo PubTag voreingestellt. Die Dauer des Tests ist nicht begrenzt. Auszahlungen von Provisionen jedoch sind ohne einen von shopwelt.de zugewiesenen PubTag und ohne vorherige Registrierung nicht möglich.</td>
				</tr>
				<tr>
					<td><b>Schriftart:</b></td>
					<td><input type="text" name="evFont" value="<?php echo $Opt['Font']; ?>" size="20"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Schriftgr&ouml;&szlig;e:</b></td>
					<td><input type="text" name="evFSize" value="<?php echo $Opt['FSize']; ?>" size="5"> px</td>
					<td></td>
				</tr>
				<tr>
					<td><b>Farbe der Trennlinie:</b></td>
					<td><input type="text" name="evCSpl" value="<?php echo $Opt['CSpl']; ?>" size="15"><span class="evcolorboxdefault" style="background-color:<?php echo $Opt['CSpl']; ?>"></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Hintergrundfarbe:</b></td>
					<td><input type="text" name="evCBg" value="<?php echo $Opt['CBg']; ?>" size="15"><span class="evcolorboxdefault" style="background-color:<?php echo $Opt['CBg']; ?>"></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Linkfarbe:</b></td>
					<td><input type="text" name="evCLnk" value="<?php echo $Opt['CLnk']; ?>" size="15"><span class="evcolorboxdefault" style="background-color:<?php echo $Opt['CLnk']; ?>"></span></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Maximale Anzahl der Angebote:</b></td>
					<td><input type="text" name="evOffCnt" value="<?php echo $Opt['OffCnt']; ?>" size="5"></td>
					<td>Hier wird fest definiert wie viele Angebote das Widget ausgegeben soll. <br />
					Sollten keine Angebote vorhanden sein, wird das Widget nicht angezeigt.</td>
				</tr>
				
				<tr>
					<td></td>
					<td><input type="submit" name="Submit" class="button-primary" value="Speichern" /></td>
					<td></td>
				</tr>
			</table></p>
		</form>
		</div></div>
			<div class="postbox">
			<h3 class="hndle"><span>Informationen zum Plugin</span></h3>
			<div class="inside">
			<p>Das shopwelt.de – WordPress Plugin ermöglicht die einfache Integration eines Preisvergleiches von Produkten an jeder gewünschten Stelle eines Posts Ihres WordPress Blogs. Angaben zu Preisen, Verfügbarkeit und Versandkosten aus z.Zt ca. über 60 Millionen Angeboten werden ständig aktuell gepflegt und stehen fast in Echtzeit zur Verfügung.</p>

			<p><b>Einbinden des Widgets</b></p>
			
      <p>Über den Beitrags-Editor (egal ob Visuell oder Text) wird einfach an der gewünschten Stelle das Widget mit eckigen Klammern (Shortcode) aktiviert/eingefügt: <br><br><b><em>[evwidget=SUCHBEGRIFF]</em></b> <br><br> Das gewünschte Produkt wird einfach als Suchbegriff direkt ohne Leerzeichen oder Anführungszeichen nach dem „=“ angefügt.</p>
      <p><b>WICHTIG!</b></p>
      <p>Der Suchbegriff sollte so genau wie möglich angegeben werden, also z.B. „iPhone 6 64 GB“ und nicht einfach „iPhone“, oder „Apple“. Dies bewirkt nur ungenaue und unbefriedigende Ergebnisse.</p>
      <p>Über shopwelt.de Datenbank werden dann im fertig erstellten Beitrag automatisch die entsprechenden Angebote angezeigt. Dabei passt sich die Anzeige und Darstellung des shopwelt.de Widgets automatisch der verfügbaren Breite an.</p>
			
			<p>Beispiel: [evwidget=iPhone 6 64GB]<br /><br /><img src="<?php echo plugins_url( 'beispiel.png', __FILE__ ) ?>" /></p>
			
			</div></div>
	</div><?php 
}

function addShopweltPagePluginSettings($links) {
	$mylinks = array('<a href="' . admin_url( 'options-general.php?page=' . plugin_basename(__FILE__) ) . '">'.__('Settings').'</a>');
	return array_merge( $links, $mylinks );
}

function addShopweltToAdminMen() { 
	add_options_page('shopwelt.de-Widget', 'shopwelt.de-Widget', 8, __FILE__, 'ShopweltAdminOptions');
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'addShopweltPagePluginSettings', 10, 2);
add_action('wp_footer', 'addShopweltWidgetFooter');
add_filter('the_content', 'addShopweltWidgetToPost');
add_action('admin_menu', 'addShopweltToAdminMen');
?>