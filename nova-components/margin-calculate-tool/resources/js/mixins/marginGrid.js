import {
  mapGetters,
} from 'vuex';

export default {
  created() {
    this.$store.watch(
      () => this.$store.getters.marginsAreSyncing,
      (marginsAreSyncing, prevMarginsAreSyncing) => {
        if (!marginsAreSyncing && prevMarginsAreSyncing) {
          this.updateSelectedMargins();
        }
      }
    );
  },

  props: {
    caratMin: {
      type: Number,
      required: true,
      default: 0,
    },
    caratMax: {
      type: Number,
      required: true,
      default: 0,
    },
  },

  data() {
    return {
      marginGrid: new Map(),
    };
  },

  computed: {
    ...mapGetters([
      'shapeTypes',
      'defaultMargins',
      'selectedMargins',
      'selectedManufacturer',
    ]),

    floatCaratMin() {
      return parseFloat(this.caratMin);
    },

    floatCaratMax() {
      return parseFloat(this.caratMax);
    },
  },

  methods: {
    getMarginNestedPath(params) {
      const isRound = _.has(params, 'isRound')
        ? _.get(params, 'isRound', 0)
        : this.isRound(_.get(params, 'shapeType', ''));
      const color = _.get(params, 'color', '');
      const clarity = _.get(params, 'clarity', '');

      return [
        _.get(params, 'manufacturer', this.selectedManufacturer),
        this.caratRange,
        isRound,
        clarity,
        color,
      ];
    },

    getMarginNestedPathString(nestedPathArray) {
      return nestedPathArray.join(',');
    },

    getMarginValue(nestedPath) {
      const nestedPathString = this.getMarginNestedPathString(nestedPath);

      if (!this.marginGrid.has(nestedPathString)) {
        this.marginGrid.set(nestedPathString, 0);
      }

      return this.marginGrid.get(nestedPathString);
    },

    setMarginValue(nestedPath, value) {
      this.marginGrid.set(this.getMarginNestedPathString(nestedPath), parseFloat(value));
    },

    updateSelectedMargins() {
      const testRegExp = new RegExp(`^${this.selectedManufacturer}`);

      for (let marginKey of this.marginGrid.keys()) {
        if (testRegExp.test(marginKey)) {
          this.marginGrid.delete(marginKey);
        }
      }

      this.selectedMargins
        .filter((marginObj) => {
          return marginObj.carat_min === this.floatCaratMin
            && marginObj.carat_max === this.floatCaratMax
        })
        .forEach((marginObj) => {
          const nestedPath = this.getMarginNestedPath({
            manufacturer: marginObj.manufacturer.slug,
            color: marginObj.color.slug,
            clarity: marginObj.clarity.slug,
            isRound: marginObj.is_round,
          });

          this.setMarginValue(nestedPath, marginObj.margin);
        });
    },
  },
};