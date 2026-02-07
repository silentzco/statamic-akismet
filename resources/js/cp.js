import Queues from './pages/Queues.vue';
import SpamListing from './components/SpamListing.vue';

Statamic.booting(() => {
    Statamic.$components.register('spam-listing', SpamListing);
    Statamic.$inertia.register('akismet::Queues', Queues);
});
