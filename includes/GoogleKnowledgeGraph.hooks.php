<?php

/**
 * Хуки расширения GoogleKnowledgeGraph 
 */
class GoogleKnowledgeGraphHooks {

	/**
	 * Добавляем подгрузку модуля 'ext.GoogleKnowledgeGraph' для всех страниц. 
	 * Описание этого модуля есть в extension.json. 
	 */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		$out->addModules( 'ext.GoogleKnowledgeGraph' );

		/**
		 * 'return true' означает, что инициализация парсера должна продолжиться в штатном режиме.
		 * Иными словами, возвращаем всегда true.
		 */ 
		return true;
	}

	
	/**
	 * Расширяем парсер, добавляя обработку тега <GoogleKnowledgeGraph> 
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
