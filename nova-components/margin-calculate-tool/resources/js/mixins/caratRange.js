export default {
  props: {
    baseCaratRange: {
      type: [String],
      default: '',
    },
  },

  created() {
    if (this.baseCaratRange) {
      const {0: caratMin, 1: caratMax} = this.baseCaratRange.split(' - ');

      this.caratMin = caratMin;
      this.caratMax = caratMax;
    }
  },

  data() {
    return {
      caratMin: '',
      caratMax: '',
    };
  },

  computed: {
    caratRange() {
      return `${this.caratMin} - ${this.caratMax}`;
    },

    caratRangeIsValid() {
      const validation = this.validateCaratValues(this.caratMin, this.caratMax);

      return validation === true;
    },
  },

  methods: {
    validateCaratValues(caratMin, caratMax) {
      const formatedCaratMin = parseFloat(caratMin);
      const formatedCaratMax = parseFloat(caratMax);

      if (!formatedCaratMin || formatedCaratMin < 0) {
        return new Error(this.getTranslation('validation.carat_min.invalid'));
      }

      if (!formatedCaratMax || formatedCaratMax < 0) {
        return new Error(this.getTranslation('validation.carat_max.invalid'));
      }

      if (formatedCaratMin >= formatedCaratMax) {
        return new Error(this.getTranslation('validation.carat_range.invalid'));
      }

      return true;
    },

    createCaratRange() {
      this.$swal({
        title: this.getTranslation('margin_carat_ranges'),
        html: `
            <span class="swal-carat-range-block">
                <input
                    id="swal-carat-min"
                    class="swal2-input"
                    placeholder="${this.getTranslation('carat_min_placeholder')}"
                >

                <span class="swal-carat-divider"> - </span>

                <input
                    id="swal-carat-max"
                    class="swal2-input"
                    placeholder="${this.getTranslation('carat_max_placeholder')}"
                >
            </div>
          `,
        focusConfirm: false,
        preConfirm: () => {
          const caratMin = document.getElementById('swal-carat-min').value;
          const caratMax = document.getElementById('swal-carat-max').value;

          const error = this.validateCaratValues(caratMin, caratMax);

          if (error instanceof Error) {
            this.$swal.showValidationMessage(error);
          }

          return [
            caratMin,
            caratMax
          ]
        }
      }).then((formData) => {
        const {0: caratMin, 1: caratMax} = formData.value;

        this.caratMin = caratMin;
        this.caratMax = caratMax;

        this.$emit('carat-range-created', [caratMin, caratMax]);
      });
    },
  },
};