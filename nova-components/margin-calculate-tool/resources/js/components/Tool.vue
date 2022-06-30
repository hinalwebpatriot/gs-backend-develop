<template>
    <div>
        <loading-view v-if="baseDataIsLoading"/>

        <div v-if="!baseDataIsLoading">
            <heading class="mb-6">
                {{ getTranslation('title') }}
            </heading>

            <empty-margin-table
                    v-if="marginsAreEmpty"
            />

            <div
                    v-if="!marginsAreEmpty"
            >
                <div class="flex justify-between">
                    <manufacturer-list/>

                    <sync-with-default/>

                    <carat-range-list/>
                </div>

                <margin-list/>
            </div>
        </div>
    </div>
</template>

<script>
  import {
    mapGetters,
    mapMutations,
    mapActions,
  } from 'vuex';
  import store from '../store';
  import translations from '../mixins/translations';
  import marginSyncWatching from '../mixins/marginSyncWatching';
  import {
    CLEAR_ERRORS,
  } from '../store/mutation-types';

  import manufacturerList from './ManufacturerList.vue';
  import caratRangeList from './CaratRangeList.vue';
  import emptyMarginTable from './EmptyMarginTable.vue';
  import marginList from './MarginList.vue';
  import syncWithDefault from './syncWithDefault.vue';


  export default {
    mixins: [
      translations,
      marginSyncWatching,
    ],

    created() {
      this.$store.registerModule('base', store);
      this.$store.watch(
        () => this.$store.getters.errors,
        (errors) => {
          if (errors.length === 0) {
            return;
          }

          this.marginErrors = [
            ...this.marginErrors,
            ...errors.map((error) => this.getErrorTranslation(error.message)),
          ];

          this.clearStoreErrors();
        }
      );
    },

    destroyed() {
      this.$store.unregisterModule('base');
    },

    mounted() {
      this.loadParams();
      this.loadTranslations();
      this.loadManufacturers();
      this.loadColors();
      this.loadClarities();
      this.loadDefaultMargins();
    },

    data() {
      return {
        marginErrors: [],
        displayingErrors: false,
      };
    },

    computed: {
      ...mapGetters([
        'appParams',
        'baseDataIsLoading',
        'hasError',
        'errors',
        'defaultMargins',
      ]),

      marginsAreEmpty() {
        return this.defaultMargins.length === 0;
      },
    },

    methods: {
      ...mapMutations({
        clearStoreErrors: CLEAR_ERRORS,
      }),

      ...mapActions([
        'loadTranslations',
        'loadManufacturers',
        'loadColors',
        'loadClarities',
        'loadDefaultMargins',
        'loadParams',
      ]),

      getErrorTranslation(messageCode) {
        return this.getTranslation(`validation.margin.${messageCode}`) || messageCode;
      },

      showErrors() {
        const error = this.marginErrors.shift();

        if (!error) {
          this.displayingErrors = false;

          return;
        }

        this.$noty.error(error, {
          layout: 'bottomRight',
          timeout: 5000,
          killer: true,
        });
      },
    },

    watch: {
      marginErrors() {
        if (!this.displayingErrors) {
          this.displayingErrors = true;
          this.showErrors();
        }
      },
    },

    components: {
      manufacturerList,
      caratRangeList,
      emptyMarginTable,
      marginList,
      syncWithDefault,
    },
  }
</script>
