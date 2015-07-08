<?php

namespace IdnoPlugins\Sitemap\Pages {

    /**
     * Default class to serve the Minds callback
     */
    class Sitemap extends \Idno\Common\Page {

	
	
	function getContent() {
	    
	    $xml = \IdnoPlugins\Sitemap\Main::load();
	    
	    if (!$xml)
		$xml = \IdnoPlugins\Sitemap\Main::generate ();
	    
	    if ($xml) {
		
		header("Pragma: public");
                header("Cache-Control: public");
		header("Content-Type: application/xml");
		
		echo $xml;
	    }
	    else 
	    {
		$this->noContent();
	    }
	}

    }

}