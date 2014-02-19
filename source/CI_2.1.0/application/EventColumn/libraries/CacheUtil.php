<?php
	/**
	 * Description of CacheUtil
	 *
	 * @author stretch
	 */
	class CacheUtil extends N8_Error {

		protected $ci;

		public function __construct() {
			parent::__construct();
			$this->ci =& get_instance();
			$this->ci->load->driver('cache', array('adapter' => 'apc'));
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
			$this->ci->cache->save($cache_key, $data, $ttl);
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
	}

?>
