<?php

/**
 * Хуки расширения GoogleKnowledgeGraph 
 */
class GoogleKnowledgeGraphHooks {

	/**
	 * Сработает хук после окончания работы парсера, но перед выводом html. 
	 * Детали тут: https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
	 */
	public static function onBeforeHtmlAddedToOutput( OutputPage &$out, ParserOutput $parserOutput ) {
		// Добавляем подгрузку модуля фронтенда для всех страниц, его определение ищи в extension.json
		$out->addModules( 'ext.GoogleKnowledgeGraph' );
		return true;
	}

	
	/**
	 * Расширяем парсер, добавляя обработку тега <GoogleKnowledgeGraphHooks>
	 */
	public static function onParserSetup( Parser $parser ) {
		$parser->setHook( 'GoogleKnowledgeGraph', 'GoogleKnowledgeGraphHooks::processGoogleKnowledgeGraphTag' );
		return true;
	}


	/**
	 * Реализация обработки тега <GoogleKnowledgeGraph> 
	 */
	public static function processGoogleKnowledgeGraphTag( $input, array $args, Parser $parser, PPFrame $frame ) {
		// Парсим аргументы переданные в формате <GoogleKnowledgeGraph arg1="val1" arg2="val2" ...> 
		if( isset( $args['query'] ) ){
			$query = $args['query'];
		}
		else{
			// В тег не был передан аргумент query, так что и выводить нам нечего
			return '';
		}

		return '<span class="googleKnowledgeGraph">' . htmlspecialchars( $query ) . '</span>';
	}

}