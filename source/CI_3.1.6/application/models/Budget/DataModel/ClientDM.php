<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of ClientDM
	 *
	 * @author stretch
	 */
	class Budget_DataModel_ClientDM extends N8_Model {

		const TABLE_NAME = 'client';

		/**
		 * @var int
		 */
		private $id;

		/**
		 * @var string
		 */
		private $name;

		/**
		 * @var string
		 */
		private $token;

		/**
		 * @param mixed $where
		 */
		public function __construct($where = []) {
			parent::__construct();

			if(!empty($where)) {
				$this->load($where);
			}
		}

		/**
		 * @param mixed $where
		 * @throws Exception
		 */
		public function load($where) {
			$query = $this->db->get_where(self::TABLE_NAME, $where);

			if(!$query || $query->num_rows() != 1) {
				throw new Exception('failure loading client.');
			}

			$this->id = $query->row()->id;
			$this->name = $query->row()->name;
			$this->token = $query->row()->token;
		}

		/**
		 * @return int
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 * @return string
		 */
		public function getToken() {
			return $this->token;
		}
	}
