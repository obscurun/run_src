<?php
// ****************************************************************************************************************************
class File{
	//*************************************************************************************************************************
	function File(){

	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function saveFile($fileTmp, $fileName, $path="", $prefix=""){
		$pathFile = "";
		if(substr($path, -1) != "/") $path .= "/";
		$pathFile = FILES_PATH.$path;

		if (!file_exists($pathFile)) {
			//Debug::p($fileTmp, $pathFile);
			mkdir($pathFile, 0777, true);
		}
		$pathFile .= $prefix.$fileName;
		//Debug::p($fileTmp, $pathFile);
		$save = rename($fileTmp, $pathFile);
		if($save) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function moveUploadedFile($fileOri, $fileName, $path="", $prefix=""){
		$pathFile = "";
		if(substr($path, -1) != "/") $path .= "/";
		$pathFile = FILES_PATH.$path;

		if(!file_exists($pathFile)) {
			mkdir($pathFile, 0777, true);
		}
		$pathFile .= $prefix.$fileName;
		//Debug::print_r($fileTmp);
		//Debug::print_r($pathFile);
		$save = move_uploaded_file($fileOri, $pathFile);
		if($save) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getFileSize($fileOri){
		if(file_exists($fileOri)) {
			return filesize($fileOri);
		}else{
			return -1;
		}
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function getBytesByUnit($value, $_unit="MB"){
        $value = floatval($value);
        $sizes = array(
            "TB" => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            "GB" => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            "MB" => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            "KB" => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            "B" => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
            "" => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
        );

        $result = $sizes[$_unit]["VALUE"] * $value;
		//Error::writeLog("getSizeByUnit: ".$result, __FILE__, __LINE__);
        return $result;
    }
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function getSizeByUnit($bytes, $_unit="MB"){
        $bytes = floatval($bytes);
        $sizes = array(
            "TB" => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            "GB" => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            "MB" => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            "KB" => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            "B" => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),            
            "" => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
        );

        $result = $bytes / $sizes[$_unit]["VALUE"];
        return $result;
    }
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function getSizeConverted($bytes){
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );
        foreach($arBytes as $arItem){
            if($bytes >= $arItem["VALUE"]){
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public function deleteFile($fileOri){
		return unlink($fileOri);
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>