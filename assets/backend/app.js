import './scss/app.scss';
import './js/templates/backend/admin/base/elements';
import 'flowbite';
import { startStimulusApp } from '@symfony/stimulus-bridge';
// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!../controllers',
    true,
    /\.(j|t)sx?$/
));

const $ = require('jquery');