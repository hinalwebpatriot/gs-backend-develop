<template>
    <div>
        <button
                type="button"
                class="btn btn-default btn-primary mt-6 w-full"
                v-if="appParams.hasUpdatePermission"
                @click="createCaratRange"
        >
            {{ getTranslation('add_margin') }}
        </button>
    </div>
</template>

<script>
  import translations from '../mixins/translations';
  import caratRange from '../mixins/caratRange';
  import marginTable from './MarginTable.vue';
  import {mapGetters} from 'vuex';

  export default {
    name: "add-margin-table",

    mixins: [
      translations,
      caratRange,
    ],

    computed: {
      ...mapGetters([
          'appParams',
      ]),
    },

    components: {
      marginTable,
    },
  }
</script>