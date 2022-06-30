<template>
    <div v-if="hasRanges">
        <div>{{ getTranslation('select_carat_range_title') }}</div>

        <select
                class="form-control form-select mb-3 w-full"
                @change="changeCaratRangeHandle"
        >
            <option
                    value=""
                    :selected="!selectedCaratRange"
            >{{ getTranslation('select_carat_range_default') }}</option>

            <option
                    v-for="caratRange in defaultCaratRanges"
                    :key="caratRange"
                    :value="caratRange"
                    :selected="selectedCaratRange === caratRange"
            >
                {{ caratRange }}
            </option>
        </select>
    </div>
</template>

<script>
  import {
    mapGetters,
    mapMutations,
  } from 'vuex';
  import {
    CHANGE_CARAT_RANGE,
  } from '../store/mutation-types';
  import translations from '../mixins/translations';

  export default {
    name: "carat-range-list",

    mixins: [translations],

    computed: {
      ...mapGetters([
        'defaultCaratRanges',
        'selectedCaratRange',
      ]),

      hasRanges() {
        return this.defaultCaratRanges.length > 0;
      }
    },

    methods: {
      ...mapMutations({
        changeCaratRange: CHANGE_CARAT_RANGE,
      }),

      changeCaratRangeHandle(event) {
        const caratRange = event.target.value;

        this.changeCaratRange({
          caratRange,
        });
      },
    },
  }
</script>