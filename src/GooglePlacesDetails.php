<?php
// add the namespace
namespace Weblab;

/**
 * Class holding a detail result returned by the google places api
 *
 * @author  Weblab.nl - Thomas Marinissen
 */
class GooglePlacesDetails {

    /**
     * The original given result as strong
     *
     * @var string
     */
    protected $originalResults;

    /**
     * The json decoded formatted result as it came from google places (details)
     *
     * @var \stdClass|null
     */
    protected $result;

    /**
     * Constructor
     *
     * @param string                The company details as the came from the google places api
     */
    public function __construct($results) {
        // store the original result
        $this->originalResults = $results;

        // format the results
        $resultsJson = json_decode($results);

        // if something went wrong, store an empty array into the results parameters
        // otherwise parse the result
        if ($resultsJson->status != 'OK') {
            $this->result = null;
        } else {
            // add the results
            $this->result = $this->parseResult($resultsJson->result);
        }
    }

    /**
     * Get the search results
     *
     * @return \stdClass[]
     */
    public function result() {
        return $this->result;
    }

    /**
     * Parse the company details from the google places
     *
     * @param   \stdClass           The company information to parse
     * @return  \stdClass           The parsed information
     */
    protected function parseResult(\stdClass $result) {
        // create the result object
        $resultObject = new \stdClass();
        $resultObject->name = $result->name;

        // set the website if available
        if (isset($result->website)) {
            $resultObject->website = $result->website;
        }

        // set the phone number
        if (isset($result->international_phone_number)) {
            $resultObject->telephone = str_replace(' ', '', $result->international_phone_number);
        }

        // get the address information
        foreach ($result->address_components as $component) {
            // get the address if it is in the current iteration
            if (in_array('route', $component->types)) {
                $resultObject->address = $component->long_name;
            }

            // get the street number if it is in the current iteration
            if (in_array('street_number', $component->types)) {
                $resultObject->number = (int) $component->long_name;
            }

            // get the zipcode if it is in the current iteration
            if (in_array('postal_code', $component->types)) {
                $resultObject->zipcode = str_replace(' ', '', $component->long_name);
            }

            // get the city if it is in the current iteration
            if (in_array('locality', $component->types)) {
                $resultObject->city = $component->long_name;
            }

            // get the country if it is in the current iteration
            if (in_array('country', $component->types)) {
                $resultObject->country = $component->long_name;
            }
        }

        // done, return the result array
        return $resultObject;
    }
}
