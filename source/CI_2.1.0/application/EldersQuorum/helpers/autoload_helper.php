<?php
    require_once(APPPATH . 'core/N8_Model.php');

    /**
     * autoload magic method, loads models and libraries without having to call $CI->load->
     *
     * @param string $classname
     */
    function __autoload($classname) {
        $dirs = array(
            APPPATH.'models/',
            APPPATH.'libraries/'
        );

        $result = false;

        if (strpos($classname, "_") !== false) {
            $result = underscoreLoadMethod($classname, $dirs);
        }

        if ($result === false) {
            $files = array($classname, lcfirst($classname));

            foreach ($dirs as $directory) {
                iterate($directory, $files);
            }
        }

//        if($result === false) {
//            legacyLoad($classname);
//        }
    }

    /**
     * load models or libraries using codeigniters native load method
     *
     * @param string $classname
     */
    function legacyLoad($classname) {
        $ci =& get_instance();
        if(file_exists(APPPATH . 'model/' . $classname . '.php')) {
            $ci->load->model($classname);
        } else if (  file_exists(APPPATH . 'libraries/' . $classname . '.php')) {
            $ci->load->library($classname);
        }
    }

    /**
     * loads the file based on the classname broken apart to create subdirectories
     *
     * @param string $classname
     * @param array  $directories
     * @since 07.01.2013
     */
    function underscoreLoadMethod($classname, $directories) {
        $result = false;
        $pieces = explode( "_", $classname );
        $i      = 1;

        foreach( $pieces as $piece ) {
            if( $i == count( $pieces ) ) {
                $piece .= ".php";
            }

            for( $j = 0; $j < count( $directories ); $j ++  ) {
                $directories[$j] .= $piece;
                $directories[$j] .= (strpos( $piece, ".php" ) === false) ? "/" : "";
            }
            $i ++;
        }

        foreach( $directories as $filepath ) {
            if( file_exists( $filepath ) ) {
                require_once($filepath);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * iterates through the directory looking for the file requested
     *
     * @param string $directory
     * @param array  $files
     */
    function iterate($directory, array $files) {
        $ignore = array(".", "..");
        $result = false;
        $directory_iterator = new DirectoryIterator($directory);

        while ($directory_iterator->valid()) {
            if ($directory_iterator->isDir() === true && !in_array($directory_iterator->getFilename(), $ignore)) {
                $result = iterate($directory_iterator->getPathname(), $files);
            } elseif ($directory_iterator->isFile()) {
                if (in_array($directory_iterator->getBasename('.php'), $files)) {
                    require_once($directory_iterator->getPathname());
                    $result = true;
                }
            }

            if ($result === true) {
                break;
            }
            $directory_iterator->next();
        }

        return $result;
    }

?>
