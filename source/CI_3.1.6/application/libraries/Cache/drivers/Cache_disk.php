<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/28/2018
 * Time: 7:37 PM
 */
class Cache_disk extends CI_Driver {

    const CACHE_DISK_LOCATION = APPPATH.'logs/cache/';
    /**
     * @var string
     */
    private $cache_path;

    /**
     * Cache_disk constructor.
     */
    public function __construct() {
        $location = get_instance()->config->item('cache_disk_path');

        $this->cache_path = !empty($location) ? $location : self::CACHE_DISK_LOCATION;
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function get($id) {
        $this->sanitizeId($id);
        if(file_exists($this->cache_path . $id)) {
            $file_data = json_decode(file_get_contents($this->cache_path . $id));
            $file_data->data = unserialize($file_data->data);

            return $file_data->data;
        }
    }

    /**
     * @param      $id
     * @param      $data
     * @param int  $ttl
     * @param bool $raw
     */
    public function save($id, $data, $ttl = 60, $raw = false) {
        $data = ['data' => serialize($data), 'expire' => (time() + $ttl)];

        $this->sanitizeId($id);
        if($this->is_supported()) {
            file_put_contents($this->cache_path . $id, json_encode($data));
        }
    }

    /**
     * @param $id
     */
    public function delete($id) {
        $this->sanitizeId($id);
        if(file_exists($this->cache_path . $id)) {
            unlink($this->cache_path . $id);
        }
    }

    /**
     * clean cache
     */
    public function clean() {
        //loop through all files in directory and delete them
        $iterator = new DirectoryIterator($this->cache_path);
        while($iterator->valid()) {
            if(!$iterator->current()->isDot() && $iterator->current()->isFile()) {
                $this->delete($iterator->current()->getFilename());
            }

            $iterator->next();
        }
    }

    /**
     * @return bool
     */
    public function is_supported() {
        return is_writable($this->cache_path);
    }

    /**
     * @param $id
     */
    private function sanitizeId(&$id) {
        $id = str_replace(DIRECTORY_SEPARATOR, '_', $id);
    }
}