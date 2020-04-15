<?php

namespace IdnoPlugins\Sitemap {

    class Main extends \Idno\Common\Plugin {

	function registerEventHooks() {
	    
	    // New data saved, force a regeneration (TODO: parse and add the url?)
	    \Idno\Core\site()->addEventHook('saved', function (\Idno\Core\Event $event) {
		$item = $event->data()['object'];
		
		if ($item instanceof \Idno\Entities\ActivityStreamPost) {
		    $obj = $item->getObject();
		    if ($obj) {
			$url = htmlentities($obj->getUrl());
			$date = date(DATE_W3C, $obj->updated);
			
			
			$new = "<url>\n<loc>$url</loc>\n<lastmod>$date</lastmod>\n<changefreq>monthly</changefreq>\n</url>\n";
			$xml = self::load();
			if (!$xml) $xml = '';
			self::store($new . $xml);
		    }
		}
	    });
	}

	function registerPages() {
	    
	    // Sitemap
	    \Idno\Core\site()->addPageHandler('sitemap\.xml', '\IdnoPlugins\Sitemap\Pages\Sitemap');
	    
	    // Extend robots
	    \Idno\Core\site()->template()->extendTemplate('txt/robots', 'Sitemap/txt/robots');
	}
	
	/**
	 * Generate a sitemap.xml file
	 */
	public static function generate() {
	    
	    $xml = "";
	    $types = \Idno\Common\ContentType::getRegisteredClasses();
	    $offset = 0;
	    $limit = 50;
	    
            while ($feed  = \Idno\Core\site()->db()->getObjects($types, [], [], $limit, $offset)) {//\Idno\Entities\ActivityStreamPost::getFromX($types, [], array(), 1, $offset)) {
		
		foreach ($feed as $obj) {  
		    if ($obj) {
			$url = htmlentities($obj->getUrl());
			$date = date(DATE_W3C, $obj->updated);
			
			$xml .= "<url>\n<loc>$url</loc>\n<lastmod>$date</lastmod>\n<changefreq>monthly</changefreq>\n</url>\n";
		    }
		}
		
		$offset+=$limit;
	    }
	        
	    self::store($xml);
	    return $xml;
	}
		
	/**
	 * Attempt to find a stored XML file.
	 */
	public static function load() {
	    
	    // We have a caching interface
	    if (isset(\Idno\Core\site()->cache)) {
		return \Idno\Core\site()->cache()->load('sitemap.xml');
	    }
	    
	    // No caching interface, use generic data item.
	    if ($sitemaps = \Idno\Entities\GenericDataItem::getByDatatype('Sitemap/Sitemap')) {
		$sitemap = $sitemaps[0];

		return $sitemap->xml;
	    }
	    
	    return false;
	}
	
	
	/**
	 * Save a raw XML file.
	 * @param string $xml Raw xml
	 */
	public static function store($xml) {
	    
	    // We have a caching interface
	    if (isset(\Idno\Core\site()->cache)) {
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

    }

}
