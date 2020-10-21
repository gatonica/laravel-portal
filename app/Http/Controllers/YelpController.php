<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Stevenmaguire\Yelp\ClientFactory;

class YelpController extends Controller
{
    /**
     * Make a Bussiness search in YELP API 
     *
     * @param  [string] queryString
     * @param  [string] location

     * @OA\Get(path="/api/yelp/search/{queryString}/location/{location}",
     *   tags={"Yelp"},
     *   summary="Bussiness search in Yelp API",
     *   description="",
     *   operationId="searchYelp",
     *   @OA\Parameter(
     *     name="queryString",
     *     required=true,
     *     in="path",
     *     description="Any word to search",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="location",
     *     required=true,
     *     in="path",
     *     description="Any location to search",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Yelp error"
     *   )
     * )
     */    
    public function searchYelp($queryString, $location)
    {
        $options = array(
            'apiKey' => 'usSzVhDW7Lo8Yn0hwwgR9-vaWPG7hB4DN_bNumi--yYWB5S0z7O5y2IBRTOj33Y_AscBsmijGqCMYDl96-cHfqTPX9fqDJxS97hoSLdfVEGcvG6graz-wNC_JS2KX3Yx', // Required, unless accessToken is provided
        );
        
        $client = ClientFactory::makeWith(
            $options,
            \Stevenmaguire\Yelp\Version::THREE
        );

        $parameters = [
            'term' => $queryString,
            'location' => $location,
        ];
        
        $results = $client->getBusinessesSearchResults($parameters);

        return response()->json($results);
    }
}
