<?php

/**
 * Hooks used by GoogleKnowledgeGraph extension 
 */
class GoogleKnowledgeGraphHooks {
	
	/**
	 * We extend parser here.
	 * Parser will process our custom tag: <GoogleKnowledgeGraphHooks>
	 */
	public static function onParserSetup( Parser $parser ) {
		$parser->setHook( 'GoogleKnowledgeGraph', 'GoogleKnowledgeGraphHooks::processGoogleKnowledgeGraphTag' );
		return true;
	}


	/**
	 * Implementation of the '<GoogleKnowledgeGraph>' tag processing
	 */
	public static function processGoogleKnowledgeGraphTag( $input, array $args, Parser $parser, PPFrame $frame ) {
		// Here we parse arguments passed in format: <GoogleKnowledgeGraph arg1="val1" arg2="val2" ...> 
		if( isset( $args['query'] ) ){
			$query = $args['query'];
		}
		else{
			// If parameter 'query' is not presented in the tag, we have nothing to output
			return '';
		}

		/**
		 * This is the perfect place to load our frontend.
		 * The frontend module 'ext.GoogleKnowledgeGraph' is defined in extension.json
		 */ 
		$parser->getOutput()->addModules( ['ext.GoogleKnowledgeGraph'] );

		/**
		 * <GoogleKnowledgeGraph query="MyQuery"> will be replaced with <span class="googleKnowledgeGraph">MyQuery</span>
		 * Tooltip will be added by JavaScript code during postprocessing after the page is loaded.
		 */
		return '<span class="googleKnowledgeGraph">' . htmlspecialchars( $query ) . '</span>';
	}

}