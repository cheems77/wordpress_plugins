<?php
		/*
		Plugin Name: xTreme Banner Display
		Plugin URI: http://www.cibydesign.co.uk
		Description: Plugin for displaying banners including flash!
		Author: C. Ijeoma
		Version: 1.1
		Author URI: http://www.cibydesign.co.uk
		*/

// Parse $_GET values
function xtrban_return_get($field) {
	if ( isset($_GET[$field]) && trim($_GET[$field]) != '' )
		return trim($_GET[$field]);
	return false;
}

// Parse $_POST values
function xtrban_return_post($field) {
	if ( isset($_POST[$field]) && trim($_POST[$field]) != '' )
		return trim($_POST[$field]);
	return false;
}

// Delete banner
function xtrban_delete_banner($banner_id=false) {
	
	if (!$banner_id)
		return  xtrban_();

	global $wpdb;

	$filename = $wpdb->get_var( "SELECT realpath FROM `" . $wpdb->prefix . "xtreme_banners` WHERE `id` = " . $banner_id );
	@unlink( $filename );

	$q = "DELETE FROM `".$wpdb->prefix ."xtreme_banners` WHERE `id` = " . $banner_id;
	$wpdb->query($q);
	return('Banner has been successfully deleted!');
	
	 xtrban_show_banners_table();
}

function xtrban_add_banner() {

echo "<div class='wrap'>";
?>
<?php echo "<h2>" . __( 'xTreme Banners Uploader', 'xtrban_trdom' ) . "</h2>"; ?>
<div id="message">
<p>Upload any image type as a banner, including flash!</p>
<p>Your image file (flash or otherwise) should not exceed the dimensions 468 x 60 px</p>
</div>
<hr />
<form name="xtrban_form" enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="xtrban_hidden" value="add_record">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<table class="widefat" cellspacing="0">
<tbody>
<tr>
<td scope="row"><?php _e("Choose a file to upload: " ); ?></td><td><input type="file" name="xtrban_uploadedfile"></p></td>
</tr>
<tr>
<td><?php _e("Description of file: " ); ?></td><td><input type="text"	name="xtrban_description"></p></td>
</tr>
<tr>
<td><?php _e("URL: " ); ?></td><td><input type="text" name="xtrban_url"><?php _e(" ex: http://www.nakedbeatz.com" ); ?></td>
</tr>
<tr>
<td><?php _e("Target: " ); ?></td><td>
<select name="xtrban_target">
<option selected="selected" value="_blank">_blank</option>
<option value="_parent">_parent</option>
<option value="_self">_self</option>
<option value="_top">_top</option>
</select><?php _e(" If you're unsure of this option, leave as '_blank' " ); ?></p></td>
</tr>
<tr>
<td>Enabled?: </td><td><input type="checkbox" name="showbanner" checked value="1"/></td>
</tr>
<tr>
<td>&nbsp;</td><td><input type="submit" class="button-primary" name="Submit" value="<?php _e('Upload file', 'xtrban_trdom' ) ?>" /></td>
</tr>
</tbody>
</table>
</form>
<hr/>
<?php
}
function xtrban_edit_banner($banner_id) {

global $wpdb;
$query = "SELECT * FROM `" . $wpdb->prefix . "xtreme_banners` WHERE id = " . $banner_id;
$banner = $wpdb->get_row($query);
?>
<div class="wrap">
<?php echo "<h2>" . __( 'xTreme Banners - Edit a Banner', 'xtrban_trdom' ) . "</h2>"; ?>
<div id="message">
<p>Edit your existing banner details here (Currently editing banner ID: <?php echo $banner->id; ?>)</p>
</div>
<hr />
<form name="xtrban_form" enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="xtrban_hidden" value="edit_record">
<input type="hidden" name="banner_id" value="<?php echo $banner->id; ?>">
<table cellspacing="0" width="60%" class="widefat">
<tbody>
<tr>
<td scope="row"><?php _e("Description of file: " ); ?></td><td><input type="text" name="xtrban_description" value="<?php echo $banner->description; ?>"></p></td>
</tr>
<tr>
<td scope="row"><?php _e("URL: " ); ?></td><td><input type="text" name="xtrban_url"  value="<?php echo $banner->url; ?>"></td>
</tr>
<tr>
<td scope="row"><?php _e("Target: " ); ?></td><td>
<select name="xtrban_target">
<option value="_blank">_blank</option>
<option value="_parent">_parent</option>
<option value="_self">_self</option>
<option value="_top">_top</option>
<option selected value="<?php echo $banner->target; ?>"><?php echo $banner->target; ?></option>
</select></p></td>
</tr>
<tr>
<td>Enabled?: </td><td><input type="checkbox" name="showbanner" <?php ($banner->showbanner ? "checked" : ""); ?> value="1"/></td>
</tr>
<tr>
<td scope="row">&nbsp;</td><td><input type="submit" class="button-primary" name="Update" value="<?php _e('Update', 'xtrban_trdom' ) ?>" /></td>
</tr>
</tbody>
</table>
</form>
<hr />
<?php
}
function xtrban_show_banners_table() {

global $wpdb;
$query = "SELECT * FROM `".$wpdb->prefix."xtreme_banners`";
$banners = $wpdb->get_results($query);
?>
<?php
echo "<h2>" . __( 'xTreme Banners', 'xtrban_trdom' ) . "</h2>";
?>
<div class="wrap">
<table class="widefat" cellspacing="0">
<thead>
	<tr>
		<th scope="col">ID</th>
		<th scope="col">banner</th>		
		<th scope="col">description</th>
		<th scope="col">URL</th>
		<th scope="col">File type</th>
		<th scope="col">Target</th>
		<th scope="col">Enabled</th>				
		<th scope="col">Action</th>
	</tr>
</thead>
<tbody>
<?php
if ($banners) {
foreach ($banners as $banner) {
print "<tr>";
echo "<td>" . $banner->id . "</td>";
echo ($banner->filetype != IMAGETYPE_SWC ? "<td><img src='" . $banner->filename . "' height='30' width='90' /></td>" : "<td>No preview available</td>");
echo "<td>" . $banner->description . "</td>";
echo "<td>" . $banner->url . "</td>";
echo "<td>" . xtrban_check_filetype($banner->filetype) . "</td>";
echo "<td>" . $banner->target . "</td>";
echo "<td>" . ($banner->showbanner ? 'Yes' : 'No') . "</td>";
?>
<td><a href="<?php echo wp_nonce_url("options-general.php?page=xtreme-banners&amp;action=delete&amp;banner_id=".$banner->id, 'xtrban_delete_banner'); ?>"' onclick="return confirm('<?php _e('You are about to delete this ad banner.', 'xTreme'); ?> \n\n <?php _e("Click \\'Cancel\\' to stop, \\'OK\\' to delete.", 'xTreme')?>')" class="delete" ><?php _e('delete', 'xTreme'); ?></a>
| <a href="<?php echo wp_nonce_url("options-general.php?page=xtreme-banners&amp;action=edit&amp;banner_id=".$banner->id, 'xtrban_edit_banner'); ?>"><?php _e('edit', 'xTreme'); ?></a></td> 
<?php
print "</tr>";
	} 
}else {
echo "<td colspan='7'>No banners available! Why don't you upload one...</td>";	
}
?>
</tbody>
<tfoot>
	<tr>
		<th scope="col">ID</th>
		<th scope="col">banner</th>			
		<th scope="col">description</th>
		<th scope="col">URL</th>
		<th scope="col">File type</th>
		<th scope="col">Target</th>		
		<th scope="col">Enabled</th>		
		<th scope="col">Action</th>
	</tr>
</tfoot>
</table>
<a href="options-general.php?page=xtreme-banners&amp;action=add"><?php _e('Add a new banner', 'xTreme'); ?></a>
</div>
<?php			

	if (xtrban_return_get('action') == 'add') {
		xtrban_add_banner();
	} elseif (xtrban_return_get('action') == 'edit' && xtrban_return_get('banner_id')) {
		xtrban_edit_banner(xtrban_return_get('banner_id'));
	} elseif (xtrban_return_get('action') == 'delete' && xtrban_return_get('banner_id')) {
		xtrban_delete_banner(xtrban_return_get('banner_id'));
	} elseif (xtrban_return_get('action') == 'update' && xtrban_return_get('banner_id')) {
		xtrban_update_banner(xtrban_return_get('banner_id'));
	}			
			}
			
			function xtrban_admin_actions() {
				add_options_page("xTreme Banners", "xTreme Banners", 1, "xtreme-banners", "xtrban_show_banners_table");
			}
	
			add_action('admin_menu', 'xtrban_admin_actions');
		
			register_activation_hook(__FILE__,'xtrban_banners_install');
			
			$xtrban_db_version = "1.0";
	
	function xtrban_install () {
	global $wpdb;
	global $xtrban_db_version;
	
	$table_name = $wpdb->prefix . "xtreme_banners";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	
	$installed_ver = get_option( "xtrban_db_version" );
	
	if( $installed_ver != $xtrban_db_version ) {
	
	$sql = "CREATE TABLE `" . $table_name . "` (
`id` int(11) NOT NULL auto_increment,
`filetype` int(11) NOT NULL,
`description` varchar(255) NOT NULL,
`width` int(4) NOT NULL,
`height` int(4) NOT NULL,
`url` varchar(255) NOT NULL,
`target` varchar(32) NOT NULL,
`filename` varchar(255) NOT NULL,
`realpath` varchar(255) NOT NULL,
`showbanner` tinyint(1) NOT NULL default '0',
PRIMARY KEY  (`id`),
UNIQUE KEY id (id)
);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	update_option( "xtrban_db_version", $xtrban_db_version );
	}
	
	add_option("xtrban_db_version", $xtrban_db_version);
	
	}
	}
	
	function xtrban_check_filetype($filetype) {
		switch ($filetype) {
case IMAGETYPE_GIF://1  	
	$extension = "gif";
	break;
case IMAGETYPE_JPEG://2 	
	$extension = "jpeg";
	break;
case IMAGETYPE_PNG://3 	
	$extension = "png";
	break;
case IMAGETYPE_SWF://4 	
	$extension = "flash";
	break;
case IMAGETYPE_BMP://6 	
	$extension = "bitmap";
	break;
case IMAGETYPE_SWC://13
	$extension = "flash";
	break;
	}
	return $extension;
}
function xtrban_get_data() {
	global $wpdb;

	$query = "SELECT * FROM `" . $wpdb->prefix . "xtreme_banners` where showbanner = 1 order by rand()";
	$banner = $wpdb->get_row($query);

if($banner) {
$_banner_data = array(
'filename' => $banner->filename, 
'type' => $banner->filetype, 
'target' => $banner->target,
'width' => $banner->width,
'height' => $banner->height);

return $_banner_data;
	}
}

function xtrban_wp_head() { 

$_banner = xtrban_get_data();
if ($_banner) {
echo "<script type='text/javascript'>\n";
echo "swfobject.registerObject('myId', '9.0.115', '".$_banner['filename']."')\n";
echo "</script>";
}
}
add_action('init','my_init_method');
add_action('wp_head', 'xtrban_wp_head');

function my_init_method() {
wp_enqueue_script('xtrbanner_obj',WP_PLUGIN_URL.'/xtreme-banners/swfobject/swfobject.js');
}
	
	function get_xtrban_banner() {

$banner = xtrban_get_data();
if ($banner) {
	if ($banner['type'] != 13) {
		$return_banner = "<a href='" . $banner['url'] . "' target='" . $banner['target'] . "' title='".$banner['description']."'>";
		$return_banner.="<img src='" . $banner['filename'] . "' width='".$banner['width']."' height='".$banner['height']."' alt='".$banner['description']."'>";
		$return_banner.="</a>";
	} else {
		$return_banner = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='468' height='60'>\n";
		$return_banner.="<param name='movie' value='".$banner['filename']."' />\n";
		$return_banner.="<!--[if !IE]>-->\n";
		$return_banner.="<object type='application/x-shockwave-flash' data='".$banner['filename']."' width='468' height='60'>\n";
		$return_banner.="<!--<![endif]-->\n";
		$return_banner.="<p>NakeDBeatZ flanner Ad Banners</p>\n";
		$return_banner.="<!--[if !IE]>-->\n";
		$return_banner.="</object>\n";
		$return_banner.="<!--<![endif]-->\n";
		$return_banner.="</object>\n";

		$return_banner .="<!---- Start of xTreme Banner ----->\n";
		$return_banner.="\n<script type='text/javascript'>\n";
		$return_banner.="swfobject.embedSWF('".$banner['filename']."', '".$banner['description']. "', '468', '60', '9.0.0');\n";
		$return_banner.="</script>\n";
		$return_banner .="<!---- End xTreme Banner ----->\n";
	}	
		return $return_banner;	
		}
	}
if($_POST['xtrban_hidden'] == 'add_record') {
	//Form data sent
	global $wpdb, $_POST, $_FILES;

	if( $_FILES['xtrban_uploadedfile']['error'] == 0 ) {
		
		// perform that clever check for flash type thing that we love
		list($width, $height, $type, $arr) = getimagesize($_FILES['xtrban_uploadedfile']['tmp_name']);
		
		$size = floor( $_FILES['xtrban_uploadedfile']['size'] / (1024*1024) );
		$name = $_FILES['xtrban_uploadedfile']['name'];
		$temp = $_FILES['xtrban_uploadedfile']['tmp_name'];

		$description = $_POST['xtrban_description'];
		$url = $_POST['xtrban_url'];
		$target = $_POST['xtrban_target'];
		$showbanner = ($_POST['showbanner'] ? $_POST['showbanner']:0);

		$uploads = wp_upload_bits( strtolower($name), '', '' );

		if ( move_uploaded_file( $_FILES['xtrban_uploadedfile']['tmp_name'], $uploads['file'] )) {

			$q = "INSERT INTO `" . $wpdb->prefix . "xtreme_banners`" .
" (`filetype`, `description`, `width`, `height`, `url`, `filename`, `target`, `realpath`,`showbanner` )" .
" VALUES (" . $type . ", '" . $description . "', " . $width . "," . $height . ", '" . 
			$url . "', '" . $uploads['url'] . "', '" . $target . "', '" . $uploads['file'] . "'," . $showbanner . ")";
			$wpdb->query($q);
			return( '' );
		} else {
			return ( '<div id="result">Impossibile spostare e posizionare il file ' . $_FILES['xtrban_uploadedfile']['name'] .
  ' (' . $_FILES['xtrban_uploadedfile']['size'] . ' bytes). Errore ' . $_FILES['xtrban_uploadedfile']['error'] . '</div>' );
		}
	} else {
		return( '<div id="result">Impossibile trasferire il file ' . $_FILES['xtrban_uploadedfile']['name'] .
 ' (' . $_FILES['xtrban_uploadedfile']['size'] . ' bytes). Errore ' . $_FILES['xtrban_uploadedfile']['error'] . '</div>' );
	}
} elseif($_POST['xtrban_hidden'] == 'edit_record') {
	
		if (!$_POST['banner_id'])
		return  xtrban_show_banners_table();
	
	//Form data sent
	global $wpdb, $_POST;

		$banner_id 	= $_POST['banner_id'];
		$description 	= $_POST['xtrban_description'];
		$url 		= $_POST['xtrban_url'];
		$target 	= $_POST['xtrban_target'];
		$showbanner = ($_POST['showbanner'] ? $_POST['showbanner']:0);


		$q = "UPDATE `" . $wpdb->prefix . "xtreme_banners`" .
"set `description` = '{$description}', " .
"`url` = '{$url}', " .
"`target` = '{$target}', " .
"`showbanner` = {$showbanner} " .
" WHERE `id` = " . $banner_id;

	$wpdb->query($q);
	return('Banner has been successfully updated!');
	
	xtrban_show_banners_table();
}
?>