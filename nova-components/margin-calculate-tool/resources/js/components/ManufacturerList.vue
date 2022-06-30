<template>
    <div>
        <div>{{ getTranslation('select_manufacturer_title') }}</div>

        <select
                v-if="hasManufacturers"
                class="form-control form-select mb-3 w-full"
                @change="changeManufacturerHandle"
        >
            <option
                    value=""
                    :selected="!selectedManufacturer"
            >{{ getTranslation('select_manufacturer_default') }}</option>

            <option
                    v-for="manufacturer in manufacturers"
                    :key="manufacturer.slug"
                    :value="manufacturer.slug"
                    :selected="selectedManufacturer === manufacturer.slug"
            >
                {{ manufacturer.title }}
            </option>
        </select>
    </div>
</template>

<script>
  import {
    mapGetters,
    mapMutations,
    mapActions,
  } from 'vuex';
  import {
    CHANGE_MANUFACTURER,
  } from '../store/mutation-types';
  import translations from '../mixins/translations';

  export default {
    name: "manufacturer-list",

    mixins: [translations],

    computed: {
      ...mapGetters([
        'manufacturers',
        'defaultMargins',
        'selectedManufacturer',
      ]),

      hasManufacturers() {
        return this.manufacturers.length > 0 && this.defaultMargins.length > 0;
      }
    },

    methods: {
      ...mapMutations({
        changeManufacturer: CHANGE_MANUFACTURER,
      }),

      ...mapActions([
        'loadMargins',
      ]),

      changeManufacturerHandle(event) {
        const manufacturer = event.target.value;

        this.changeManufacturer({
          manufacturer,
        });
        this.loadMargins(manufacturer);
      },
    },
  }
</script>