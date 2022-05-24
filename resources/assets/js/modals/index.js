import { init as searchInit } from './search';
import { init as rolesInit } from './roles';
import { init as activityInit } from './activity';
import { init as invalidateSessionsInit } from './invalidateSessions';
import { init as feedbackInit } from './feedback';

function init() {
	searchInit();
	rolesInit();
	activityInit();
	invalidateSessionsInit();
	feedbackInit();
}

export { init };
