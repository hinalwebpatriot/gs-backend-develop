<template>
    <button
            v-if="selectedManufacturer && appParams.hasUpdatePermission"
            class="btn btn-default btn-primary"
            @click="syncMarginsWithDefaultHandler"
            type="button"
    >
        {{ getTranslation('sync_with_default') }}
    </button>
</template>

<script>
  import {
    mapGetters,
    mapActions,
  } from 'vuex';
  import translations from '../mixins/translations';

  export default {
    name: "sync-with-default",

    mixins: [
      translations,
    ],

    computed: {
      ...mapGetters([
        'selectedManufacturer',
        'appParams'
      ]),
    },

    methods: {
      ...mapActions([
        'syncMarginsWithDefault'
      ]),

      syncMarginsWithDefaultHandler() {
        this.syncMarginsWithDefault(this.selectedManufacturer);
      },
    }
  }
</script>