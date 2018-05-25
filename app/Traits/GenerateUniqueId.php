<?php

namespace App\Traits;

trait GenerateUniqueId
{
    function generateUniqueId($model = '')
	{
		$cl = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $id_deflen = '8';
        
		if (!empty($model)) {
			$modelname = $model;
		} else {
            $name_class = get_class($this);
            $name_class_arr = explode('\\', $name_class);
			$modelname = $name_class_arr[count($name_class_arr) - 1];
		}

		do {
			$id = '';
			
			$today = getdate();
			$id .= $cl[($today['year']%100)%strlen($cl)];
			$id .= $cl[$today['mon']];
			$id .= $cl[$today['mday']];
			$id .= $cl[$today['hours']];
			$id .= $cl[$today['minutes']];
			$id .= $cl[$today['seconds']];
		
			list($usec, $sec) = explode(' ', microtime());
			mt_srand((float)$sec + ((float)$usec * 100000));
			for ($i = 0; $i < 2; $i++) {
				$id .= $cl[mt_rand(0, strlen($cl)-1)];
			}

			if (isset($this->idFirstLetter)) {
				if (strlen($this->idFirstLetter) > 2) {
					$this->idFirstLetter = substr($this->idFirstLetter, 0, 2);
				}
				
				$fl_len = strlen($this->idFirstLetter);
				$id = substr($id, $fl_len);
				
				$id = $this->idFirstLetter . $id;
			}

			if (!empty($model)) {
				$data = $this->{$modelname}->where('id', $id)->first();
				if (!$data) {
					break;
				}
			} else {
				break;
			}
		} while(0);

        $this->attributes['id'] = $id;
        if (empty($this->attributes['created_id']) && $modelname == 'User') {
            $this->attributes['created_id'] = $id;
        }
        if (empty($this->attributes['modified_id']) && $modelname == 'User') {
            $this->attributes['modified_id'] = $id;
        }
	}
}
