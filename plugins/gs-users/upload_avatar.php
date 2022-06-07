<?php
include('../../admin/inc/common.php');

if (!defined('GSIMAGEWIDTH')) {
	$width = 200; //New width of image  	
} else {
	$width = GSIMAGEWIDTH;
}
	
if ($_POST['sessionHash'] === $SESSIONHASH) {
	if (!empty($_FILES)){
		
		$tempFile  = $_FILES['avatar-img']['tmp_name'];
		
		$file      = $_FILES['avatar-img']['name'];
		$extension = pathinfo($file,PATHINFO_EXTENSION);

  		$name      = pathinfo($file,PATHINFO_FILENAME);
		$name      = clean_img_name(to7bit($name));

		$targetPath = (isset($_POST['path'])) ? GSDATAUPLOADPATH.$_POST['path']."/" : GSDATAUPLOADPATH;
		$targetFile =  str_replace('//','/',$targetPath) . $name . '.'.$extension;
		
		//validate file
		if (validate_safe_file($tempFile, $_FILES['avatar-img']["name"])) {
			move_uploaded_file($tempFile, $targetFile);
			if (defined('GSCHMOD')) {
				chmod($targetFile, GSCHMOD);
			} else {
				chmod($targetFile, 0644);
			}
		} else {
			die(i18n_r('ERROR_UPLOAD') . ' - ' . i18n_r('BAD_FILE'));
			// invalid file
		}
		 
		$path = (isset($_POST['path'])) ? $_POST['path']."/" : "";			
		require(GSADMININCPATH.'imagemanipulation.php');	
		genStdThumb(isset($_POST['path']) ? $_POST['path']."/" : '',$name.'.'.$extension);	

		die(json_encode(array('code' => 0, 'url' => $path.$name.'.'.$extension)));
		// success
	} else {
		die(json_encode(array('code' => 1, 'error' => i18n_r('ERROR_UPLOAD') . ' - ' . i18n_r('MISSING_FILE'))));
		// nothing sent
	}
} else {
	die(i18n_r('ERROR_UPLOAD') . ' - ' . i18n_r('API_ERR_AUTHFAILED'));
	// Wrong session hash!
}