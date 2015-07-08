<?php

namespace IdnoPlugins\Sitemap\Pages {

    /**
     * Default class to serve the Minds callback
     */
    class Callback extends \Idno\Common\Page {

	/**
	 * Attempt to find a stored XML file.
	 */
	protected function load() {
	    
	}
	
	
	/**
	 * Save a raw XML file.
	 * @param string $xml Raw xml
	 */
	protected function store($xml) {
	    
	}
	
	function getContent() {
	    
	    $xml = $this->load();
	    
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