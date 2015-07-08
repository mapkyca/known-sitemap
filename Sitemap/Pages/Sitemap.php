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
		
		
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		echo $xml;
		echo "</urlset>";
	    }
	    else 
	    {
		$this->noContent();
	    }
	}

    }

}