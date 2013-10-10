<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class FW_export {

	public $filename 		= 'excel-doc';
	public $custom_titles;

	public function make_from_db($db_results) {
		$data 		= NULL;
//		$fields 	= $db_results->field_data();
		$fields		= $db_results->result();

		if ($db_results->num_rows() == 0) {
			show_error('Parece no haber datos para mostrar');
		}
		else {
			$titlesArray = (array) $fields[0];
			$headers = $this->titles($titlesArray);

			foreach ($db_results->result() AS $row) {
				$line = '';
				foreach ($row AS $value) {
					if (!isset($value) OR $value == '') {
						$value = "\t";
					}
					else {
						$value = str_replace('"', '""', $value);
						$value = '"' . $value . '"' . "\t";
					}
					$line .= $value;
				}
				$data .= trim($line) . "\n";
			}
			$data = str_replace("\r", "", $data);
			
			$this->generate($headers, $data);
		}
	}

	public function make_from_array($titles, $array) {
		$data = NULL;

		if (!is_array($array)) {
			show_error('The data supplied is not a valid array');
		}
		else {
			$headers = $this->titles($titles);

			if (is_array($array)) {
				foreach ($array AS $row) {
					$line = '';
					foreach ($row AS $value) {
						if (!isset($value) OR $value == '') {
							$value = "\t";
						}
						else {
							$value = str_replace('"', '""', $value);
							$value = '"' . $value . '"' . "\t";
						}
						$line .= $value;
					}
					$data .= trim($line) . "\n";
				}
				$data = str_replace("\r", "", $data);

				$this->generate($headers, $data);
			}
		}
	}

	private function generate($headers, $data) {
		$this->set_headers();

		echo "$headers\n$data";  
	}

	public function titles($titles) {
		$titles = array_keys($titles);
		if (is_array($titles)) {
			$headers = array();
			
			if (is_null($this->custom_titles)) {
				if (is_array($titles)) {
					foreach ($titles AS $title) {
						$headers[] = $title;
					}
				}
				else {
					foreach ($titles AS $title) {
						$headers[] = $title->name;
					}
				}
			}
			else {
				$keys = array();
				foreach ($titles AS $title) {
					$keys[] = $title->name;
				}
				foreach ($keys AS $key) {
					$headers[] = $this->custom_titles[array_search($key, $keys)];
				}
			}
			return '"'.implode("\"\t\"", $titles).'"';
		}
	}

	private function set_headers() {
		header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");;
	    header("Content-Disposition: attachment;filename=$this->filename.xls");
	    header("Content-Transfer-Encoding: binary ");
	}
}