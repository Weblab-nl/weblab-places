<?php
// add the namespace
namespace Weblab;

/**
 * Class holding a result returned by the google places api
 * 
 * @author  Weblab.nl - Thomas Marinissen
 */
class GooglePlacesSearch {
    
    /**
     * The original given result as strong
     * 
     * @var string
     */
    protected $originalResults;
    
    /**
     * The json decoded result as it came from google places api
     * 
     * @var \stdClass[]
     */
    protected $results;
    
    /**
     * Constructor
     * 
     * @param string                The result as it was provided by the places api
     */
    public function __construct($results) {
        // store the original result
        $this->originalResults = $results;
        
        // format the results
        $resultsJson = json_decode($results);
        
        // if something went wrong, store an empty array into the results parameters
        // otherwise add the results
        if ($resultsJson->status != 'OK') {
            $this->results = array();
        } else {
            // add the results
            $this->results = $resultsJson->results;
        }
    }
    
    /**
     * Get the search results
     * 
     * @return \stdClass[]
     */
    public function results() {
        return $this->results;
    }
    
    /**
     * Check to see if the GooglePlacesSearch result is empty or not
     * 
     * @return boolean              Whether the search result in the google places api is empty or not
     */
    public function isEmpty() {
        return count($this->results) == 0;
    }
}
