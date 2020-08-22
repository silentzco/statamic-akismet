import SpamListing from './components/SpamListing.vue';

Statamic.booting(() => {
    Statamic.$components.register('spam-listing', SpamListing);
});
