import Queues from './pages/Queues.vue';
import SpamListing from './pages/SpamListing.vue';

Statamic.booting(() => {
    Statamic.$inertia.register('akismet::SpamListing', SpamListing);
    Statamic.$inertia.register('akismet::Queues', Queues);
});
