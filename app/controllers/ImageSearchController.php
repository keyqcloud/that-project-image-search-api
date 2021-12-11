<?php

// References:
// https://stackoverflow.com/questions/138313/how-to-extract-img-src-title-and-alt-from-html-using-php

class ImageSearchController extends \Kyte\Mvc\Controller\ModelController
{
    public function hook_init() {
        $this->allowableActions = ['new']; // only allow post request
        $this->requireAuth = false; // make controller public
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
        if (count($result[0]) > 2) {
            for ($i = 1;$i<count($result[0]);$i++) {
                $src = "";
        
                preg_match_all('/src=("[^"]*")/i',$result[0][$i],$src);

                // remove src=" and trim trailing quotation
                $response[] = rtrim(str_replace("src=\"", "", $src[0][0]),"\"");
            }
         }

        // return result
        $this->response['data'] = $response;
    }
}

?>
