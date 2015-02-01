<?php
    require_once('/var/www/source/CI_2.1.0/system/core/Model.php');

    /**
     * N8_Loader: extends CI_Loader - loads classes for CI
     *
     * @author stretch
     */
    class N8_Loader extends CI_Loader {

        /**
         * Model Loader - overwrites CI_Loader::model(), loads models but does not instantiate them
         * and does not require the model filename to be camelcase.
         *
         * This function lets users load and instantiate models.
         *
         * @param	string	the name of the class
         * @param	string	name for the model
         * @param	bool	database connection
         * @return	void
         */
        public function model($model, $name = '', $db_conn = true) {
            if (is_array($model)) {
                foreach ($model as $babe) {
                    $this->model($babe);
                }
                return;
            }

            if ($model == '') {
                return;
            }

            $path = '';

            // Is the model in a sub-folder? If so, parse out the filename and path.
            if (($last_slash = strrpos($model, '/')) !== FALSE) {
                // The path is in front of the last slash
                $path = substr($model, 0, $last_slash + 1);

                // And the model name behind it
                $model = substr($model, $last_slash + 1);
            }

            if ($name == '') {
                $name = $model;
            }

            if (in_array($name, $this->_ci_models, TRUE)) {
                return;
            }

            $CI = & get_instance();
            if (isset($CI->$name)) {
                show_error('The model name you are loading is the name of a resource that is already being used: ' . $name);
            }

            //do not require lowercase filenames
//            $model = strtolower($model);

            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'models/' . $path . $model . '.php')) {
                    continue;
                }

                if ($db_conn !== FALSE AND !class_exists('CI_DB')) {
                    if ($db_conn === TRUE) {
                        $db_conn = '';
                    }

                    $CI->load->database($db_conn, FALSE, TRUE);
                }

                if (!class_exists('CI_Model')) {
                    load_class('Model', 'core');
                }

                require_once($mod_path . 'models/' . $path . $model . '.php');

                $model = ucfirst($model);

                //remove the class instantiation, we only want the require_once()
//                $CI->$name = new $model();

                $this->_ci_models[] = $name;
                return;
            }

            // couldn't find the model
            show_error('Unable to locate the model you have specified: ' . $model);
        }
    }
