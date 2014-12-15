<?php
    /**
     * Factory Class for Ad services like google adsense
     *
     * @author stretch
     */
    class AdFactory {
        const AD_MEDIUM_RECTANGLE = 'medium_rectangle';
        const AD_AUTO             = 'auto';

        private static $add_services = array('adsense');

        /**
         * instantiates the ad service class
         *
         * @param string $ad_service
         * @return \AdFactory_AdFactoryInterface
         * @throws UnexpectedValueException
         */
        public static function getAdService($ad_service = 'adsense') {
            if(in_array($ad_service, self::$add_services)) {
                switch($ad_service) {
                    case 'adsense':
                        $ad_service_obj = new AdFactory_Adsense();
                        break;
                }

                return $ad_service_obj;
            } else {
                throw new UnexpectedValueException("$ad_service not a valid ad service for ".__CLASS__);
            }
        }
    }
