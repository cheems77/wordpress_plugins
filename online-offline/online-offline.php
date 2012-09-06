<?php
/*
Plugin Name: Online Offline Plugin Script (OOPS)
Plugin URI: http://cibydesign.co.uk/plugins/oops/
Description: Plugin to manually set and show online or offline status of...
Author: Chima Ijeoma
Version: 1.0
Author URI: http://cibydesign.co.uk
*/

if (!class_exists("OOPS")) {
	class OOPS {

		var $adminOptionsName = "OOPSAdminOptions";

        	function __construct() {
			
        	}
	
		/**
		 * Activation function
		 * @return
		 */
	        function install() {
		
	        }
	
		/**
		 * Deactivate function
		 * @return
		 */
	        function uninstall() {
			
	        }


		/**
		 * Return an array of admin options
		 */
		function getAdminOptions() {
			$oopsAdminOptions = array('oops_enable_widget' => 'true',
				'oops_player_status' => 'true');
			$oopsOptions = get_option($this->adminOptionsName);
			if (!empty($oopsOptions)) {
				foreach ($oopsOptions as $key => $option)
					$oopsAdminOptions[$key] = $option;
			}
			update_option($this->adminOptionsName, $oopsAdminOptions);
			return $oopsAdminOptions;
		}

		function oops_init() {
			$this->getAdminOptions();
		}

		function updateDashboardValue() {
			$oopsOptions = $this->getAdminOptions();

			_e("Set Status to: " . $this->setPlayerStatusToggler($oopsOptions['oops_player_status'],"string"),"OOPS");
                        ?>
<input type="hidden" name="oops_enable_widget" value="true" />
<input type="hidden" name="oops_player_status" value="<?php echo $this->setPlayerStatusToggler($oopsOptions['oops_player_status'],"value"); ?>" />
			<?php
			$oopsOptions['oops_enable_widget'] = 'true';
			if (isset($_POST['oops_player_status'])) {
				$oopsOptions['oops_player_status'] = $_POST['oops_player_status'];
				update_option($oops->adminOptionsName, $oopsOptions);
			}
		}

		/**
		 * Print out the admin page
		 */
		function printAdminPage() {
			$oopsOptions = $this->getAdminOptions();
			if (isset($_POST['update_oopsSettings'])) {
				if (isset($_POST['oops_enable_widget'])) {
					$oopsOptions['oops_enable_widget'] = $_POST['oops_enable_widget'];
				}
				if (isset($_POST['oops_player_status'])) {
					$oopsOptions['oops_player_status'] = $_POST['oops_player_status'];
				}
				update_option($this->adminOptionsName, $oopsOptions);
				?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "OOPS");?></strong></p></div>
				<?php
			} ?>

<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h2>OOPS! (Online/Offline Plugin Script)</h2>
<h3>Enable widget?</h3>
<p>Selecting "Yes" will display the code in the administration panel making it easier to switch online or offline. (Note: This feature is currently in development and is not affected by any updates)</p>
<p><label for="oops_enable_widget_yes"><input type="radio" id="oops_enable_widget_yes" name="oops_enable_widget" value="true" <?php if ($oopsOptions['oops_enable_widget'] == "true") { _e('checked="checked"', "OOPS"); }?> /> Yes</label>	<label for="oops_enable_widget_no"><input type="radio" id="oops_enable_widget_no" name="oops_enable_widget" value="false" <?php if ($oopsOptions[''] == "false") { _e('checked="checked"', "OOPS"); }?>/> No</label></p>
<h3>Set Status of Widget?</h3>
<p>Selecting "off" will mark the widget as being 'offline'.</p>
<p><label for="oops_player_status_yes"><input type="radio" id="oops_player_status_on" name="oops_player_status" value="true" <?php if ($oopsOptions['oops_player_status'] == "true") { _e('checked="checked"', "OOPS"); }?> /> On</label>	<label for="oops_player_status_no"><input type="radio" id="oops_player_status_no" name="oops_player_status" value="false" <?php if ($oopsOptions['oops_player_status'] == "false") { _e('checked="checked"', "OOPS"); }?>/> Off</label></p>
<div class="submit">
<input type="submit" name="update_oopsSettings" value="<?php _e('Update Settings', 'OOPS') ?>" /></div>
</form>
 </div>
				<?php
			}


		function oops_dashboard_widget_function() {
			$oopsWidget = new OOPS();
			$_oops_settings = $oopsWidget->getAdminOptions();
			if (isset($_POST['update_oopsSettings'])) {
				$_oops_settings['oops_player_status'] = $_oops_settings['oops_player_status']=='true' ? 'false' : 'true';
			}
			update_option($oopsWidget->adminOptionsName, $_oops_settings);
			// Display whatever it is you want to show
			?>
			<div id="oopsStatus">
			<span style="float: left; margin-top: 5px;" >The NakeDBeatZ video streaming is currently <?php echo $oopsWidget->getStringPlayerStatus($_oops_settings['oops_player_status']); ?></span>
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<input type="hidden" name="update_oopsSettings" value="true" />
				<input type="submit" value="Change" class='button-secondary' style="margin-left: 10px;"/>
			</form>
			</div>
			<?php 
		}

		private function getStringPlayerStatus($status) {

			if(!is_null($status)) {
				$string_status = ($status == "true" ? 'Online' : 'Offline');
			}
			return $string_status;
		}

		private function setPlayerStatusToggler($status,$type) {
	
			if(!is_null($status)) {
				$string_status = ($status == "true" ? 'Offline' : 'Online');
			}
			$val_status = ($string_status == "Offline" ? 'false' : 'true');
			$status_toggler = "<a href='?update_oopsSettings&amp;oops_player_status=" . $val_status . "' target='_self'>[Turn " . $string_status . "]</a>";
			return ($type == "string" ? $string_status : $val_status);
		}

		// Create the function to use in the action hook
		function oops_add_dashboard_widgets() {
			wp_add_dashboard_widget('oops_dashboard_widget', 'OOPS (Online Offline Plugin Script)', array(__CLASS__,'oops_dashboard_widget_function'),array(__CLASS__,'updateDashboardValue'));
		}

function oops_widget() {
		
	$_oops_settings = $this->getAdminOptions();
	
	//Set path to plugin
	$pluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

	//Set CSS
	wp_enqueue_style('streamwidgetstyle', get_bloginfo('wpurl') . '/wp-content/plugins/stream-links-widget/style.css');
	
	
	// Display whatever it is you want to show
	switch($_oops_settings['oops_player_status']) {
		case "true":
		?>
<div id="stream-widgets">
		<h3 class="widgettitle">Nakedbeatz Streams</h3>
<img id="ooimage" src="<?php echo $pluginpath; ?>images/nb_online.gif" style="border: 1px solid #666;" alt='NakeDBeatZ Online Image' title='NakeDBeatZ Radio and Video - Online' border='0' width='298' height='40'>
<p>Current status of Nakedbeatz streaming radio</p>			
<a href="http://shoutcast.internet-radio.org.uk/tunein.php/nakedbeatz/playlist.ram" rel="http://shoutcast.internet-radio.org.uk/tunein.php/nakedbeatz/playlist.ram" title="Play NakedBeatz Radio with Real Player" class="stream-widget">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_realplayer_icon.png" title="Play Nakedbeatz Radio Stream with Realplayer">
			</a>
			<a target="_self" href="http://shoutcast.internet-radio.org.uk/tunein.php/nakedbeatz/playlist.qtl" title="Itunes/Quick Time" class="stream-widget" title="Play NakedBeatz Radio with Itunes" class="stream-widget">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_itunes_icon.png" title="Play Nakedbeatz Radio Stream with Itunes">
			</a>
			<a target="_self" href="http://shoutcast.internet-radio.org.uk/tunein.php/nakedbeatz/playlist.asx" title="Windows Media Player" class="stream-widget" title="Play NakedBeatz Radio with Windows Media Player" class="stream-widget">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_mediaplayer_icon.png" title="Play Nakedbeatz Radio Stream with Windows Media Player">
			</a>
			<a target="_self" href="http://shoutcast.internet-radio.org.uk/tunein.php/nakedbeatz/playlist.pls" title="Play NakedBeatz Radio with Winamp Player" class="stream-widget">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_winamp_icon.png" title="Play Nakedbeatz Radio Stream with Winamp">
			</a>
<a href="http://www.nakedbeatz.com/video" onclick="return popWin('popvid1');" title="Watch NakedbeatzTV (popup)" class="stream-widget popup">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_tv_icon.png" title="Watch NakedbeatzTV (popup)">
			</a>
			<a target="_self" href="http://www.nakedbeatz.com/apps/#android" title="Get the Android App for NakedBeatz Radio" class="stream-widget">
				<img width="40" height="40" alt="" src="<?php echo $pluginpath; ?>images/oo_androidplayer_icon.png" title="Play Nakedbeatz Radio Stream with Android App">
			</a>
</div>
<div id="popvid1" style="display: none;">
<object id="MediaPlayer" width=320 height=240 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">
<param name="filename" value="mms://195.189.12.41/nakedbeatz">
</param><param name="Showcontrols" value="false">
</param><param name="autoStart" value="false">
</param><param name="ShowStatusBar" value="true">
<embed type="application/x-mplayer2" src="mms://195.189.12.41/nakedbeatz" width=320 height=240></embed> 
</param></object>
</div>
		<?php
		break;
		case "false":
		?>
		<div id="stream-widgets">	
		<img id="ooimage" src="<?php echo $pluginpath; ?>images/nb_offline.gif" style="border: 1px solid #666;" alt='NakeDBeatZ Video Offline Image' title='NakeDBeatZ Video - Offline' border='0' width='298' height='40'>
		</div>
		<?php
		break;
	}
}

function oops_menu()
{
    global $wpdb;
    include 'oops-admin.php';
}

//Register the widget
function widget_init() {
	register_sidebar_widget( "Online Offline (OOPS)", array($this,'oops_widget' ));
	}


//Initialize the admin panel
	function admin_menu() {
		add_options_page('OOPS (Online Offline Plugin Script)', 'Online Offline Plugin Script', None, basename(__FILE__), array($this, 'printAdminPage'));
	}


} //End Class OOPS
}


if (class_exists("OOPS")) {

	//Run Plugin
	$oopsWidget = new OOPS();
	add_action('admin_menu', array(&$oopsWidget, 'admin_menu'));
	add_action('activate_online-offline/online-offline.php', array(&$oopsWidget, 'init'));
	add_action('wp_dashboard_setup', array(&$oopsWidget, 'oops_add_dashboard_widgets' ));
	add_action ('plugins_loaded', array(&$oopsWidget, 'widget_init'));

}

?>