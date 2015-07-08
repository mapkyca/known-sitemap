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
	    
	    // We have a caching interface
	    if (\Idno\Core\site()->cache) {
		return \Idno\Core\site()->cache()->load('sitemap.xml');
	    }
	    
	    // No caching interface, use generic data item.
	    $sitemaps = \Idno\Entities\GenericDataItem::getByDatatype('Sitemap/Sitemap');
	    $sitemap = $sitemaps[0];
	    
	    return $sitemap->xml;
	}
	
	
	/**
	 * Save a raw XML file.
	 * @param string $xml Raw xml
	 */
	protected function store($xml) {
	    
	    // We have a caching interface
	    if (\Idno\Core\site()->cache) {
		return \Idno\Core\site()->cache()->store('sitemap.xml', $xml);
	    }
	    
	    // No caching interface, use generic data item.
	    if ($sitemaps = \Idno\Entities\GenericDataItem::getByDatatype('Sitemap/Sitemap'))
		$sitemap = $sitemaps[0];
	    if (!$sitemap) {
		$sitemap = new \Idno\Entities\GenericDataItem();
		$sitemap->setDatatype('Sitemap/Sitemap');
	    }
	    
	    $sitemap->xml = $xml;
	    
	    return $sitemap->save();
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