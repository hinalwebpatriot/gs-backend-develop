<template>
    <card
            class="flex flex-col items-center justify-center"
    >
        <h2 class="text-black mb-6 mt-6">{{ getTranslation('margin_carat_ranges') }} {{ caratRange }}</h2>

        <div class="flex flex-row w-full">
            <div
                    class="margins-card w-1/2 p-5"
                    v-for="shapeType in shapeTypes"
            >
                <h4 class="margins-card-title flex justify-center">
                    {{ getTranslation(`margin_shapes.${shapeType}`) }}
                </h4>

                <table class="table w-full">
                    <thead>
                    <tr>
                        <th></th>
                        <th
                                v-for="clarity in clarities"
                                class="whitespace-no-wrap"
                        >
                            {{ clarity.title }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="color in colors">
                        <th>{{ color.title }}</th>
                        <td
                                :class="[
                                  'margin-value-item',
                                  {
                                    'bg-30': editingValuePath === getMarginNestedPathString(
                                        getMarginNestedPath({
                                            shapeType,
                                            clarity: clarity.slug,
                                            color: color.slug,
                                        })
                                    )
                                  }
                                ]"
                                v-for="clarity in clarities"
                        >
                            <span
                                    v-show="!editingValuePath
                                        || editingValuePath !== getMarginNestedPathString(
                                            getMarginNestedPath({
                                                shapeType,
                                                clarity: clarity.slug,
                                                color: color.slug,
                                            })
                                        )"
                                    class="whitespace-no-wrap"
                            >
                                {{
                                    getMarginValue(
                                      getMarginNestedPath({
                                        shapeType,
                                        clarity: clarity.slug,
                                        color: color.slug,
                                      })
                                    )
                                }}

                                <span
                                        v-if="selectedManufacturer && notEqualWithDefault({
                                          shapeType,
                                          clarity: clarity.slug,
                                          color: color.slug,
                                        })"
                                        :class="[
                                          'margin-hint',
                                          {
                                            'text-danger': defaultValueIsGreater({
                                              shapeType,
                                              clarity: clarity.slug,
                                              color: color.slug,
                                            }),
                                            'text-success': defaultValueIsLess({
                                              shapeType,
                                              clarity: clarity.slug,
                                              color: color.slug,
                                            }),
                                          }
                                        ]"
                                >
                                    {{
                                      getDiffWithDefault({
                                        shapeType,
                                        clarity: clarity.slug,
                                        color: color.slug,
                                      })
                                    }}
                                </span>
                            </span>

                            <div class="margin-value-item-controls">
                                <span
                                        :title="getTranslation('edit')"
                                        class="cursor-pointer text-70 hover:text-primary"
                                        v-show="appParams.hasUpdatePermission && editingValuePath !== getMarginNestedPathString(
                                            getMarginNestedPath({
                                                shapeType,
                                                clarity: clarity.slug,
                                                color: color.slug,
                                            })
                                        )"
                                        @click="editMarginValue(getMarginNestedPath({
                                            shapeType,
                                            clarity: clarity.slug,
                                            color: color.slug,
                                        }))"
                                >
                                    <icon type="edit"/>
                                </span>

                                <span
                                        :title="getTranslation('accept')"
                                        class="cursor-pointer text-70 hover:text-primary mr-1"
                                        v-show="editingValuePath === getMarginNestedPathString(
                                            getMarginNestedPath({
                                                shapeType,
                                                clarity: clarity.slug,
                                                color: color.slug,
                                            })
                                        )"
                                        @click="applyEditingMarginValue(
                                            shapeType,
                                            clarity.slug,
                                            color.slug
                                        )"
                                >
                                    <accept-icon/>
                                </span>

                                <span
                                        :title="getTranslation('cancel')"
                                        class="cursor-pointer text-70 hover:text-primary"
                                        v-show="editingValuePath === getMarginNestedPathString(
                                            getMarginNestedPath({
                                                shapeType,
                                                clarity: clarity.slug,
                                                color: color.slug,
                                            })
                                        )"
                                        @click="cancelEditingMarginValue()"
                                >
                                    <cancel-icon/>
                                </span>
                            </div>

                            <input
                                    v-show="editingValuePath === getMarginNestedPathString(
                                        getMarginNestedPath({
                                            shapeType,
                                            clarity: clarity.slug,
                                            color: color.slug,
                                        })
                                    )"
                                    type="number"
                                    class="form-input"
                                    v-model="tempMargin"
                            >
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </card>
</template>

<script>
  import {
    mapGetters,
    mapMutations,
    mapActions,
  } from 'vuex';
  import translations from '../mixins/translations';
  import marginGrid from '../mixins/marginGrid';
  import {
    ADD_ERROR,
  } from '../store/mutation-types';

  import acceptIcon from './Icons/Accept.vue';
  import cancelIcon from './Icons/Cancel.vue';

  export default {
    name: "margin-table",

    mixins: [
      translations,
      marginGrid,
    ],

    data() {
      return {
        swalError: null,
        tempMargin: 0,
        editingValuePath: '',
      };
    },

    computed: {
      ...mapGetters([
        'shapeTypes',
        'manufacturers',
        'colors',
        'clarities',
        'defaultMargins',
        'caratGroupedMargins',
        'defaultCaratGroupedMargins',
        'selectedManufacturer',
        'appParams',
      ]),

      caratRange() {
        return `${this.caratMin} - ${this.caratMax}`;
      },

      margins() {
        return _.get(this.caratGroupedMargins, this.caratRange, []);
      },

      defaultMargins() {
        return _.get(this.defaultCaratGroupedMargins, this.caratRange, []);
      },

      tempMarginValue() {
        return parseFloat(this.tempMargin);
      },
    },

    methods: {
      ...mapMutations({
        addError: ADD_ERROR,
      }),

      ...mapActions([
        'saveMarginOnServer',
      ]),

      isRound(shapeType) {
        return shapeType === 'round' ? 1 : 0;
      },

      fillMarginsGridByValues(margins) {
        if (this.selectedManufacturer) {
          this._fillMarginsGridByValues(this.defaultMargins);
        }

        this._fillMarginsGridByValues(margins);
      },

      _fillMarginsGridByValues(margins) {
        margins.forEach((margin) => {
          const nestedPath = this.getMarginNestedPath({
            manufacturer: _.get(margin, 'manufacturer.slug', null),
            isRound: margin.is_round,
            clarity: margin.clarity.slug,
            color: margin.color.slug,
          });

          this.setMarginValue(nestedPath, margin.margin);
        });
      },

      editMarginValue(editingPath) {
        this.tempMargin = this.getMarginValue(editingPath) || 0;
        this.editingValuePath = this.getMarginNestedPathString(editingPath);
      },

      applyEditingMarginValue(shapeType, clarity, color) {
        const nestedPath = this.getMarginNestedPath({shapeType, clarity, color});

        if (this.getMarginValue(nestedPath) !== this.tempMarginValue) {
          try {
            this.saveMarginOnServer({
              marginObj: {
                key: this.editingValuePath,
                manufacturer: this.selectedManufacturer,
                carat_min: this.caratMin,
                carat_max: this.caratMax,
                is_round: this.isRound(shapeType),
                clarity,
                color,
                previous_margin: this.getMarginValue(nestedPath),
                margin: this.tempMarginValue,
              },
              callback: (response) => {
                  if (response.data.success) {
                      this.$noty.success(
                          this.getTranslation('margin_save_success'),
                          {
                              layout: 'bottomRight',
                              timeout: 2000,
                              killer: true,
                          }
                      );
                  } else {
                      this.$noty.error(
                          response.data.message,
                          {
                              layout: 'bottomRight',
                              timeout: 4000,
                              killer: true,
                          }
                      );
                  }
              },
            });

            this.setMarginValue(nestedPath, this.tempMarginValue);
          } catch (error) {
            this.addError({error});
          }
        }

        this.cancelEditingMarginValue();
      },

      cancelEditingMarginValue() {
        this.editingValuePath = '';
      },

      getDiffWithDefault(params) {
        const nestedPath = this.getMarginNestedPath(params);
        params.manufacturer = null;
        const defaultNestedPath = this.getMarginNestedPath(params);

        const defaultValue = this.getMarginValue(defaultNestedPath);
        const existValue = this.getMarginValue(nestedPath);

        return existValue - defaultValue;
      },

      notEqualWithDefault(params) {
        return this.getDiffWithDefault(params) !== 0;
      },

      defaultValueIsGreater(params) {
        return this.getDiffWithDefault(params) < 0;
      },

      defaultValueIsLess(params) {
        return this.getDiffWithDefault(params) > 0;
      },
    },

    watch: {
      margins: {
        immediate: true,
        handler(margins) {
          this.fillMarginsGridByValues(margins);
          this.$forceUpdate();
        },
      },
    },

    components: {
      acceptIcon,
      cancelIcon,
    }
  }
</script>

<style scoped>
    .margin-hint {
        font-size: 10px;
    }

    table td:first-child,
    table th:first-child {
        position: -webkit-sticky;
        position: sticky;
        z-index: 3;
        left:0;
    }
</style>