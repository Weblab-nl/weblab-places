<?php
// add the namespace
namespace Weblab;

/**
 * Class that gives access to the google places api
 * 
 * @author  Weblab.nl - Thomas Marinissen
 */
class GooglePlaces {
    
    /**
     * The google places api base url
     * 
     * @var string
     */
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/place/%s/json';
    
    /**
     * The api key
     * 
     * @var string
     */
    protected $key;

    /**
     * The constructor made private
     */
    private function __construct($key) {
        $this->key = $key;
    }
    
    /**
     * Request the google places result object based on an address
     * 
     * @param   string                                          The search string to find a place by
     * @return  \Weblab\GooglePlaces                            The google places api result object
     */
    public static function search($searchString) {
        // the parameters needed to get the google places information
        $parameters = array(
            'query'     => $searchString,
            'language'  => 'nl',
        );
 
        // get the values from the places api
        $result = self::fetchResult($parameters);

        // if there is no valid result, return null
        if (is_null($result)) {
            return $result;
        }
        
        // done, return the google places search result as object 
        return new \Weblab\GooglePlacesSearch($result);
    }
    
    /**
     * Request the google places result object based on an address
     * 
     * @param   string                                          The search string to find a place by
     * @return  \Weblab\GooglePlacesDetails                     The google places api result object
     */
    public static function details($placeId) {
        // the parameters needed to get the google places information
        $parameters = array(
            'placeid'     => $placeId,
            'language'  => 'nl',
        );
        
        // get the values from the places api
        $result = self::fetchResult($parameters, 'details');
        
        // if there is no valid result, return null
        if (is_null($result)) {
            return $result;
        }
        
        // done, return the google places details as object 
        return new \Weblab\GooglePlacesDetails($result);
    }
    
    /**
     * Helper method that requests a result according to a given url from the
     * google places api
     * 
     * @param   array           The parameters to add to the url
     * @param   string          The type of request. textsearch|details
     * @return  string          The response of the google places api
     */
    protected function curl($parameters, $type = 'textsearch') {
        // add the api key to the parameters
        $parameters['key'] = $this->key;
        
        // create the url
        $url = sprintf($this->baseUrl, $type) . '?' . http_build_query($parameters);
        
        // prepare everything for the curl request at the google server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        // execute the curl request
        $response = curl_exec($ch);
        
        // close the curl request
        curl_close($ch);
        
        // done, return the response
        return $response;
    }
    
    /**
     * Fetch a result based on the given parameters
     * 
     * @param   array               The parameters to add to the url
     * @return  string|null         The result of fetching the places information
     */
    protected static function fetchResult($parameters, $type = 'textsearch') {
        // create a google places object to get access to the google places api
        $googlePlaces = self::instance();
        
        // get the values from the places api
        $result = $googlePlaces->curl($parameters, $type);

        // if there is no valid result, return null
        if ($result === false) {
            return null;
        }
        
        // done, return
        return $result;
    }
    
}