<?php
    if (!defined('BASEPATH'))
	exit('No direct script access allowed');

    /**
     * Description of FileUpload
     *
     * @author stretch
     */
    class FileUpload extends N8_Error {

        const DEFAULT_UPLOAD_PATH = '/media/EventColumn/event/images';

        protected $ci;
        protected $config;

        protected static $config_map = array(
            'event_image' => 'eventImageUploadConfig.ini'
        );

        public function __construct() {
            parent::__construct();
            $this->ci = & get_instance();
            $this->config = APPPATH . 'site_cfg/';
        }

        /**
         * load the config and initialize the upload library
         *
         * @param string $config
         * @throws InvalidArgumentException
         */
        public function initialize($config) {
            if( !isset(self::$config_map[$config]) ) {
                throw new InvalidArgumentException("Invalid config provided");
            }

            $this->config .= self::$config_map[$config];

            $config_array = $this->buildConfigArray();

            $this->ci->load->library( 'upload', $config_array );
        }

        /**
         * upload the file and return the upload details
         *
         * @return array
         * @throws Exception
         */
        public function doUpload() {
            if ( ! $this->ci->upload->do_upload()) {
                throw new Exception($this->ci->upload->display_errors());
            }

            $data = $this->ci->upload->data();

            return $data;
        }

        /**
         * parses the config file into the upload config array
         *
         * @return array
         */
        protected function buildConfigArray() {
            $config = parse_ini_file($this->config);

            return $config;
        }
    }
