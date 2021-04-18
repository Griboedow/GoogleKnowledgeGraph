<?php

// MediaWiki services is needed to load values of '$wgGoogleApiLanguage' and '$wgGoogleApiToken'
use MediaWiki\MediaWikiServices;


/** 
 * The class implements and describes API method 'askgoogleknowledgegraph'
 * For simplicity I don't implement caching. If you want to cache results, there is a good implementation here: 
 * https://github.com/wikimedia/mediawiki-extensions-TextExtracts/blob/master/includes/ApiQueryExtracts.php
 */
class ApiAskGoogleKnowledgeGraph extends ApiBase {

	public function execute() {

		$params = $this->extractRequestParams();
		// 'query' is mandatory parameter so $params['query'] always defined
		$description = ApiAskGoogleKnowledgeGraph::getGknDescription( $params['query'] );


		/**
		 * Define the 'Get' request result.
		 * 'Post' requst will return the same in my case. 
		 * If you want to prohibit 'Post' requests, you should write additional code
		 */
		$this->getResult()->addValue( null, "description", $description );
	}


	/** 
	 * A list of supported parameters.
	 */
	public function getAllowedParams() {
		return [
			'query' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			]
		];
	}


	/**
	 * Get data from Google Knowledge Graph, 
	 * suggesting that the first result is a correct one. 
	 * If you want to have a more specific request (for example, specify type: person/organization/etc), 
	 * you should add additional params
	 */
	private static function getGknDescription( $query ) {
		
		/**
		 * Getting parameters specified by use. 
		 * In LocalSettings.php all params have default prefix wg. For example, '$wgGoogleApiToken'.
		 * Here we specify these params without prefix
		 */
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'GoogleKnowledgeGraph' );
		$gkgToken = $config->get( 'GoogleApiToken' );
		$gkgLang = $config->get( 'GoogleApiLanguage' );


		$service_url = 'https://kgsearch.googleapis.com/v1/entities:search';
		$params = [
			'query' => $query ,
			'limit' => 1,
			'languages' => $gkgLang,
			'indent' => TRUE,
			'key' => $gkgToken,
		];

		$url = $service_url . '?' . http_build_query( $params );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url) ;
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$response = json_decode( curl_exec( $ch ), true );
		curl_close( $ch );

		if( count( $response['itemListElement'] ) == 0 ){
			return "Nothing found by your request \"$query\"";
		}
		
		if( !isset( $response['itemListElement'][0]['result'] ) ){
			return "Unknown GKG result format for request \"$query\"";
		}

		if( !isset( $response['itemListElement'][0]['result']['detailedDescription'] ) ){
			return "detailedDescription was not provided by GKG for request \"$query\"";
		}
		
		if( !isset( $response['itemListElement'][0]['result']['detailedDescription']['articleBody'] ) ){
			return "articleBody was not provided by GKG for request \"$query\"";
		}
		
		return $response['itemListElement'][0]['result']['detailedDescription']['articleBody'];
	}

}
