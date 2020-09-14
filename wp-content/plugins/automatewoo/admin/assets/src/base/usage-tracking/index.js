window.wcTracks = window.wcTracks || {};
window.wcTracks.recordEvent = window.wcTracks.recordEvent || function() {};
window.wcTracks.isEnabled = window.wcTracks.isEnabled || false;

/**
 * No-op function when Tracks is disabled.
 */
let recordTracksEvent = () => {};

if ( window.wcTracks.isEnabled ) {
	/**
	 * Record a tracking event for AutomateWoo.
	 *
	 * @param {string} eventName
	 * @param {Object} data
	 */
	recordTracksEvent = ( eventName, data ) => {
		window.wcTracks.recordEvent( `aw_${eventName}`, data );
	};
}

export default recordTracksEvent;
