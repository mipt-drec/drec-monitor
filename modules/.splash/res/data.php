<?php
	if (file_exists("img.jpg")){
		echo json_encode(array(enabled => true, hash => md5_file("img.jpg")));
	}else{
		echo json_encode(array(enabled => false));
	}
?>