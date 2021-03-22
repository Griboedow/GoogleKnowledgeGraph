( function ( mw, $ ) {
	$(".googleKnowledgeGraph").each( function() {
		var element = $( this );
		$.ajax({
			type: "GET", 
			url: mw.util.wikiScript( 'api' ),
			data: { 
				action: 'askgoogleknowledgegraph', 
				query: element.text(),
				format: 'json',
			},
			dataType: 'json',
			success: function( jsondata ){
				element.prop( 'title', jsondata.description );
			}
		});
	});
}( mediaWiki, jQuery ) );