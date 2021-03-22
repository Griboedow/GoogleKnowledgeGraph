<?php

/** 
 * Класс включает в себя реализацию и описание API метода askgoogleknowledgegraph
 * Для простоты я не реализую кеширование, любопытные могут подсмотреть реализацию тут: 
 * https://github.com/wikimedia/mediawiki-extensions-TextExtracts/blob/master/includes/ApiQueryExtracts.php
 */
use MediaWiki\MediaWikiServices;

class ApiAskGoogleKnowledgeGraph extends ApiBase {

	public function execute() {

		$params = $this->extractRequestParams();
		// query - обязательный параметр, так что $params['query'] всегда определен
		$description = ApiAskGoogleKnowledgeGraph::getGknDescription( $params['query'] );


		/**
		 * Определяем результат для Get запроса. 
		 * На самом деле Post запрос отработает с тем же успехом, 
		 * если специально не отслеживать тип запроса ¯\_(ツ)_/¯.
		 */
		$this->getResult()->addValue( null, "description", $description );
	}


	/** 
	 * Список поддерживаемых параметров метода
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
	 * Получаем данные из Google Knowledge Graph, 
     * предполагая, что самый первый результат и есть верный.
	 */
	private static function getGknDescription( $query ) {
		
		/**
		 * Вытаскиваем параметры языка и токен.
		 * Все параметры в LocalSettings.php имеют префикс wg, например wgGoogleApiToken.
		 * Здесь же мы их указываем бех префикса
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

		if( count( $response['itemListElement'] ) > 0 ){
			return $response['itemListElement'][0]['result']['detailedDescription']['articleBody'];
		}

		return '';
	}

}