<template>
    <div>
        <margin-table
                v-if="!selectedCaratRange || selectedCaratRange === range"
                v-for="range in defaultCaratRanges"
                :key="range"
                :carat-min="getCaratMin(range)"
                :carat-max="getCaratMax(range)"
        />

        <margin-table
                v-if="!selectedManufacturer"
                v-for="range in extraCaratRangeList"
                :key="range.join(' - ')"
                :carat-min="range[0]"
                :carat-max="range[1]"
        />

        <add-margin-table
                v-if="!selectedManufacturer"
                @carat-range-created="addCaratRange"
        />

        <div class="syncing_background" v-if="marginsAreSyncing"></div>
    </div>
</template>

<script>
  import {
    mapGetters,
    mapMutations,
  } from 'vuex';
  import {
    ADD_ERROR,
    CHANGE_CARAT_RANGE,
  } from '../store/mutation-types';
  import marginTable from './MarginTable.vue';
  import addMarginTable from './AddMarginTable.vue';
  import translations from '../mixins/translations';

  export default {
    name: "margin-list",

    mixins: [
      translations,
    ],

    created() {
      this.changeCaratRange({
        caratRange: _.first(this.defaultCaratRanges)
      });
    },

    data() {
      return {
        extraCaratRangeList: [],
      };
    },

    computed: {
      ...mapGetters([
        'defaultMargins',
        'selectedMargins',
        'selectedManufacturer',
        'defaultCaratRanges',
        'selectedCaratRange',
        'marginsAreSyncing',
      ]),
    },

    methods: {
      ...mapMutations({
        addError: ADD_ERROR,
        changeCaratRange: CHANGE_CARAT_RANGE,
      }),

      getCaratMin(caratRange) {
        const {0: caratMin} = Array.isArray(caratRange)
          ? caratRange
          : caratRange.split(' - ');

        return caratMin;
      },

      getCaratMax(caratRange) {
        const {1: caratMax} = Array.isArray(caratRange)
          ? caratRange
          : caratRange.split(' - ');

        return caratMax;
      },

      addCaratRange(caratRange) {
        if (this.rangeIsExist(caratRange)) {
          this.addError({
            error: new Error(this.getTranslation('carat_range_already_exist')),
          });

          return;
        }

        this.extraCaratRangeList.push(caratRange);
      },

      rangeIsExist(caratRange) {
        const {0: caratMin, 1: caratMax} = caratRange;

        return this.defaultCaratRanges
          .concat(this.extraCaratRangeList)
          .map((range) => {
            const minInRange = caratMin >= this.getCaratMin(range) && caratMin <= this.getCaratMax(range);
            const maxInRange = caratMax >= this.getCaratMin(range) && caratMax <= this.getCaratMax(range);

            return minInRange || maxInRange;
          })
          .filter((inRange) => inRange)
          .length > 0;
      },
    },

    components: {
      marginTable,
      addMarginTable,
    },
  }
</script>