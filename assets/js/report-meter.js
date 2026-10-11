/**
 * The free-read meter (Phase 7, decision #9). On a Public Field Report, a
 * signed-out visitor reads a few reports in full per rolling 8 hours; after
 * that the report is swapped for the create-account prompt.
 *
 * Counted in the browser (localStorage) because WP Engine caches signed-out
 * pages. Re-reading a counted report is free. If storage is unavailable, or
 * without JavaScript, the report stays open: these reports are Public.
 */
( function () {
	var meter = document.querySelector( '[data-report-meter]' );
	var prompt = document.querySelector( '[data-report-meter-prompt]' );
	if ( ! meter || ! prompt ) {
		return;
	}

	var KEY = 'assemble_free_reads';
	var WINDOW = 8 * 60 * 60 * 1000;
	var limit = parseInt( meter.getAttribute( 'data-free-reads' ), 10 );
	var id = meter.getAttribute( 'data-report-id' );
	var now = Date.now();
	var reads;

	if ( isNaN( limit ) || ! id ) {
		return;
	}

	try {
		reads = JSON.parse( window.localStorage.getItem( KEY ) || '[]' );
	} catch ( e ) {
		return;
	}
	if ( ! Array.isArray( reads ) ) {
		reads = [];
	}

	reads = reads.filter( function ( read ) {
		return read && typeof read.t === 'number' && now - read.t < WINDOW;
	} );

	var seen = reads.some( function ( read ) {
		return read.id === id;
	} );

	if ( ! seen && reads.length >= limit ) {
		meter.hidden = true;
		prompt.hidden = false;
		return;
	}

	if ( ! seen ) {
		reads.push( { id: id, t: now } );
	}
	try {
		window.localStorage.setItem( KEY, JSON.stringify( reads ) );
	} catch ( e ) {}
}() );
