<?php

class FileHandler
{

    private $con;

    public function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';

        $db = new DbConnect();
        $this->con = $db->connect();
    }


    public function saveFile($file, $extension, $desc, $name)
    {
        // $name = round(microtime(true) * 1000) . '.' . $extension;
        $name = $name . '.' . $extension;
        // $filedest = dirname(__FILE__) . UPLOAD_PATH . $name;
        $filedest = dirname(__FILE__) .'/'. $name;
        move_uploaded_file($file, $filedest);

        $url = $server_ip = gethostbyname(gethostname());

        $stmt = $this->con->prepare("INSERT INTO files (description, file) VALUES (?, ?)");
        $stmt->bind_param("ss", $desc, $name);
        if ($stmt->execute())
            return true;
        return false;
    }

    public function getAllFiles()
    {
        $stmt = $this->con->prepare("SELECT id, description, file FROM files ORDER BY id DESC");
        $stmt->execute();
        $stmt->bind_result($id, $desc, $url);

        $images = array();

        while ($stmt->fetch()) {

            $temp = array();
            // $absurl = 'http://' . gethostbyname(gethostname()) . '/FileUploadApi' . UPLOAD_PATH . $url;
            $absurl = 'http://' . gethostbyname(gethostname()) . '/FileUploadApi' .'/'. $url;
            $temp['id'] = $id;
            $temp['desc'] = $desc;
            $temp['url'] = $absurl;
            array_push($images, $temp);
        }

        return $images;
    }

}