<?php

namespace App\Helpers;

use DB;

class ModelHelper
{
    public static function generateUniqueId($tablename, $idFirstLetter)
	{
		$cl = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $id_deflen = '8';

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

            if (!empty($idFirstLetter)) {
				if (strlen($idFirstLetter) > 2) {
					$idFirstLetter = substr($idFirstLetter, 0, 2);
				}
				
				$fl_len = strlen($idFirstLetter);
				$id = substr($id, $fl_len);
				
				$id = $idFirstLetter . $id;
			}

			if (!empty($tablename)) {
				$data = DB::table($tablename)->where('id', $id)->first();
				if (!$data) {
					break;
				}
			} else {
				break;
			}
		} while(0);

        return $id;
	}
}
