<?php

		// json url : getting most starred repos created in the last 30 days
		$url = 'https://api.github.com/search/repositories?q=created:>'.date('Y-m-d', strtotime('-30 days')).'&sort=stars&order=desc';

		// curl : getting url content
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
        $json = curl_exec($ch); 
        curl_close($ch);   
 
		// decode the json received
		$json = json_decode($json);
		
		// groupe repos by language.
		foreach($json->items as $itm){
			$repos[$itm->language][] = $itm->html_url;
		}
		
		// calculating  
		foreach($repos as $k => $repo){
			if(empty($k)) continue; 						// ingoring repos with no language.
			 $language['name'] = $k ;						// the language name
			 $language['number_repos'] = count($repo) ;		// number of repos using this language
			 $language['list_repos'] = $repo ;				// list of repos using the language
			 $languages['items'][] = $language;
		}
		
		header('Content-Type: application/json');
		echo json_encode($languages);

