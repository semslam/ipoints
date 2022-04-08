<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploader extends CI_Controller {

	/**
     * Upload Files
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */
	
	public function upload()
	{
		$config['upload_path'] = './product_images/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$this->load->library('upload', $config);
		$this->upload->do_upload('file');

		$arr = [
			'file_name' => $this->upload->data('file_name'),
			'file_size' => $this->upload->data('file_size'),
			'file_type' => $this->upload->data('file_type'),
			'file_thumbnail' => in_array($this->upload->data('file_type'), ['image/png', 'image/jpg', 'image/jpeg']) ? base_url() . 'product_images/' . $this->upload->data('file_name') : ''
		];

		echo json_encode($arr);
	}

	/**
     * List of Uploaded Files
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function files()
	{
		$dir = "product_images/";
		$a = scandir($dir);
		$b = scandir($dir, 1);
		$page = $_GET['page'];
		$files_per_page = $_GET['files_per_page'];
		$search = $_GET['search_file'];
		$arr_new = [];

		foreach ($b as $key => $file) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $dir . $file);
			finfo_close($finfo);

			if ($mime != 'directory') {
				if (!empty($search)) {
					if (strpos($file, $search) !== false) {
						$arr_new[] = [
							'file_name' => $file,
							'file_size' => filesize($dir . $file),
							'file_type' => $mime,
							'file_thumbnail' =>  in_array(explode('/', $mime)[1], ['png', 'jpg', 'jpeg']) ? base_url() . $dir . $file : ''
						];
					}
				} else {
					$arr_new[] = [
						'file_name' => $file,
						'file_size' => filesize($dir . $file),
						'file_type' => $mime,
						'file_thumbnail' =>  in_array(explode('/', $mime)[1], ['png', 'jpg', 'jpeg']) ? base_url() . $dir . $file : ''
					];
				}
			}
		}

		$arr_paginated = array_slice($arr_new, (($page * $files_per_page) - $files_per_page), $files_per_page);

		$arr = [
			'files' => $arr_paginated,
			'total' => count($arr_new)
		];

		echo json_encode($arr);
	}

}
