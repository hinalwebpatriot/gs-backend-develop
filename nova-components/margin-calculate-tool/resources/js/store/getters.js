import marginGroup from '../lib/marginGroup';

export default {
  baseDataIsLoading: (state) => {
    return Object.keys(state.loadedData)
      .filter(loadedDataKey => !state.loadedData[loadedDataKey])
      .length > 0;
  },

  hasError: (state) => {
    return state.errors.length > 0;
  },

  errors: (state) => state.errors,

  translation: (state) => {
    return state.translation;
  },

  shapeTypes: (state) => state.marginShapeTypes,

  manufacturers: (state) => state.manufacturers,

  marginQueue: (state) => state.marginQueue,

  colors: (state) => state.colors,

  clarities: (state) => state.clarities,

  defaultMargins: (state) => state.defaultMargins,

  appParams: (state) => state.appParams,

  marginsAreSyncing: (state) => state.marginsAreSyncing,

  selectedMargins: (state, getters) => getters.selectedManufacturer
    ? state.selectedMargins
    : getters.defaultMargins,

  selectedManufacturer: (state) => state.selectedManufacturer,

  selectedCaratRange: (state) => state.selectedCaratRange,

  defaultCaratGroupedMargins: (state, getters) => {
    return marginGroup(getters.defaultMargins);
  },

  defaultCaratRanges: (state, getters) => Object.keys(getters.defaultCaratGroupedMargins),

  caratGroupedMargins: (state, getters) => {
    return !getters.selectedManufacturer
      ? getters.defaultCaratGroupedMargins
      : marginGroup(getters.selectedMargins);
  },
};