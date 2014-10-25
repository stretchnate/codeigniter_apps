<?php
	/**
	 * Description of CacheUtil
	 *
	 * @author stretch
	 */
	class CacheUtil extends N8_Error {

		protected $ci;
        protected $accepted_drivers_array = array('apc', 'memcached', 'file', 'dummy');

		public function __construct($primary_driver = 'apc', $backup_driver = 'file') {
			parent::__construct();
			$this->ci =& get_instance();

            $cache_driver_array = array();
            if(in_array($primary_driver, $this->accepted_drivers_array)) {
                $cache_driver_array['adapter'] = $primary_driver;
            } else {
                throw new UnexpectedValueException("Invalid primary driver specified in ".__METHOD__);
            }

            if(in_array($backup_driver, $this->accepted_drivers_array)) {
                $cache_driver_array['backup'] = $backup_driver;
            } else {
                throw new UnexpectedValueException("Invalid backup driver specified in ".__METHOD__);
            }

			$this->ci->load->driver('cache', $cache_driver_array);
		}

		/**
		* fetch the cached data
		* @todo need to create a more centralized cache fetching method/class
		*
		* @param  string $cache_key
		* @return array
		* @since  1.0
		*/
		public function fetchCache($cache_key) {
		   $cache_array = $this->ci->cache->get($cache_key);
		   return $cache_array;
		}

		/**
		 * saves data to cache
		 *
		 * @param string $cache_key
		 * @param mixed  $data
		 * @param int    $ttl
		 */
		public function saveCache($cache_key, $data, $ttl = 600) {
			return $this->ci->cache->save($cache_key, $data, $ttl);
		}

		/**
		 * generates a unique key
		 *
		 * @param  mixed $prefix
		 * @return string
		 */
		public static function generateCacheKey($prefix = null) {
			return uniqid($prefix, true);
		}

        /**
         * deletes an item from the cache
         *
         * @param string $cache_key
         * @return boolean
         */
        public function deleteCachedItem($cache_key) {
            return $this->ci->cache->delete($cache_key);
        }

        /**
         * clears the entire cache
         *
         * @return boolean
         */
        public function clearCache() {
            return $this->ci->cache->clean();
        }

        /**
         * returns information on the entire cache
         *
         * @return type
         */
        public function getCacheInfo() {
            return $this->ci->cache->cache_info();
        }

        /**
         * return detailed information on a specific item in the cache.
         *
         * @return type
         */
        public function getMetaData($cache_key) {
            return $this->ci->cache->get_metadata($cache_key);
        }
	}

?>
