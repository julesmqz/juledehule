<?php
defined("BASEPATH") or exit("No direct script access allowed");
require APPPATH . '/third_party/vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

class Social_model extends CI_Model
{

    public function getInstagramPics()
    {

        $this->db->order_by('uploaded_on', 'RANDOM');
        $this->db->limit(12, 0);
        $query = $this->db->get('social_instagram_pics');

        return $query->result_array();
    }

    public function insertInstaPics($pics)
    {
        foreach ($pics as &$pic) {
            if (ENVIRONMENT === "production") {
                $source = $this->downloadPic($pic["image"], true);
                $pic["image"] = $this->uploadImgToStorage($source);
            } else {
                $pic["image"] = $this->downloadPic($pic["image"]);
            }
            $this->db->replace('social_instagram_pics', $pic);
        }
    }

    public function downloadPic($url, $returnPath = false)
    {
        $dir = APPPATH . "/../assets/img/social/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $name = explode("?", basename($url))[0];
        $fullPath = $dir . "/" . $name;
        file_put_contents($fullPath, file_get_contents($url));

        return $returnPath ? $fullPath : base_url("/assets/img/social/" . $name);
    }

    public function uploadImgToStorage($source)
    {
        $bucketName = str_replace("/", '', JH_ASSETS_URL) . "/img/social";
        $storage = new StorageClient();
        $file = fopen($source, 'r');
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload($file, [
            'name' => basename($source),
        ]);

        return JH_ASSETS_URL . "/img/social/" . basename($source);
        //printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($source), $bucketName, $objectName);
    }
}
