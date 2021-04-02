( function ( mw, $ ) {
	$( ".googleKnowledgeGraph" ).each( function( index, element ) {
		$.ajax({
			type: "GET", 
			// Get API endpoint
			url: mw.util.wikiScript( 'api' ),
			data: { 
				action: 'askgoogleknowledgegraph', 
				query: $( element ).text(),
				format: 'json',
			},
			dataType: 'json',
			success: function( jsondata ){
				/**
				 * Adding results to a tooltip.
				 * You may want to add eadditional logic for the case when Knowledge Graph returns nothing.
				 */
				$( element ).prop( 'title', jsondata.description );
			}
		});
	});
}( mediaWiki, jQuery ) );
