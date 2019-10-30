<?php

require_once dirname(__FILE__) . '/FileHandler.php';

$response = array();

if (isset($_GET['apicall'])) {
	switch ($_GET['apicall']) {
		case 'upload':

		if (isset($_POST['description']) && strlen($_POST['description']) > 0 && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
			$upload = new FileHandler();

			$file = $_FILES['file']['tmp_name'];

			$name = $_POST['name'];
			$description = $_POST['description'];

			if ($upload->saveFile($file, getFileExtension($_FILES['file']['name']), $description, $name)) {
				$im = imagecreatefrompng($name .'.'. getFileExtension($_FILES['file']['name']));

				if($im && imagefilter($im, IMG_FILTER_GRAYSCALE))
				{
					imagepng($im,$name .'.'. getFileExtension($_FILES['file']['name']));
				}

				imagedestroy($im);

				$response['error'] = false;
				$response['message'] = 'File Uploaded Successfullly';
				$response['data'] = (object) array('name' => $name, 'images' => $description, );;
			}

		} else {
			$response['error'] = true;
			$response['message'] = 'Required parameters are not available';
			$response['data'] = [];
		}

		break;

		case 'getallimages':

		$upload = new FileHandler();
		$response['error'] = false;
		$response['images'] = $upload->getAllFiles();

		break;

		default:
		$response['error'] = true;
	}
}

echo json_encode($response);

function getFileExtension($file)
{
	$path_parts = pathinfo($file);
	return $path_parts['extension'];
}