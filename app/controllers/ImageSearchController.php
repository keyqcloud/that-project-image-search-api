<?php

class ImageSearchController extends \Kyte\Mvc\Controller\ModelController
{
    public function hook_init() {
        $this->allowableActions = ['new']; // only allow post request
        $this->requireAuth = false; // make controller public
        $this->requireRoles = false;
    }

    // handle post request
    public function new($data)
    {
        // check if a keyword was passed - if not, then fail with error
        if (!isset($data['keyword'])) {
            throw new \Exception("Please provide search keywords");
        }

        // create empty array to hold image urls from search result
        $response = [];

        // get Google Image Search result
        $url = "http://images.google.it/images?as_q=".urlencode($data['keyword']);
        $html = file_get_contents($url);

        // find all img tags
        preg_match_all('/<img[^>]+>/i',$html,$result);

        // iterate through each tag and grab src
        foreach($result[0] as $img_tag) {
            $src = "";
     
            preg_match_all('/src=("[^"]*")/i',$img_tag,$src);
            
            $response[] = $src[0][0];
         }

        // return result
        $this->response['data'] = $response;
    }
}

?>
