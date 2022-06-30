import {
  ADD_ERROR,
  CHANGE_CARAT_RANGE,
  CHANGE_MANUFACTURER,
  CLEAR_ERRORS,
  CLEAR_MARGIN_QUEUE,
  MARGINS_ARE_SYNCING,
  REMOVE_FIRST_FROM_MARGIN_QUEUE,
  SYNC_SELECTED_MARGINS_FROM_DEFAULT,
  UPDATE_BASE_LOAD_STATE,
  UPDATE_DEFAULT_MARGIN,
  UPDATE_MARGIN_QUEUE,
  UPDATE_SELECTED_MARGINS,
} from './mutation-types';

export default {
  [UPDATE_BASE_LOAD_STATE] (state, payload) {
    state[payload.loadedKey] = payload.loadedData;
    state.loadedData[payload.loadedKey] = payload.loadedData;
  },

  [ADD_ERROR] (state, payload) {
    state.errors.push(payload.error);
  },

  [CLEAR_ERRORS] (state) {
    state.errors = [];
  },

  [UPDATE_MARGIN_QUEUE] (state, payload) {
    state.marginQueue = payload.queue;
  },

  [REMOVE_FIRST_FROM_MARGIN_QUEUE] (state) {
    state.marginQueue.splice(0, 1);
  },

  [CLEAR_MARGIN_QUEUE] (state) {
    state.marginQueue = null;
  },

  [UPDATE_DEFAULT_MARGIN] (state, payload) {
    const marginIndex = _.findIndex(state.defaultMargins, (margin) => {
      return margin.carat_max === parseFloat(_.get(payload, 'marginObj.carat_max'))
        && margin.carat_min === parseFloat(_.get(payload, 'marginObj.carat_min'))
        && _.get(margin, 'clarity.slug') === _.get(payload, 'marginObj.clarity')
        && _.get(margin, 'color.slug') === _.get(payload, 'marginObj.color')
        && margin.is_round === _.get(payload, 'marginObj.is_round');
    });

    if (marginIndex === -1) {
      return;
    }

    const margin = state.defaultMargins[marginIndex];
    margin.margin = parseFloat(_.get(payload, 'marginObj.margin'));

    state.defaultMargins = [
      ...state.defaultMargins.slice(0, marginIndex),
      margin,
      ...state.defaultMargins.slice(marginIndex + 1),
    ];
  },

  [UPDATE_SELECTED_MARGINS] (state, payload) {
    state.selectedMargins = payload.margins || [];
  },

  [CHANGE_MANUFACTURER] (state, payload) {
    state.selectedManufacturer = payload.manufacturer || null;
  },

  [CHANGE_CARAT_RANGE] (state, payload) {
    state.selectedCaratRange = payload.caratRange || null;
  },

  [MARGINS_ARE_SYNCING] (state, isSyncing) {
    state.marginsAreSyncing = isSyncing;
  },

  [SYNC_SELECTED_MARGINS_FROM_DEFAULT] (state) {
    const manufacturer = _.find(state.manufacturers, {slug: state.selectedManufacturer});

    state.selectedMargins = state.defaultMargins.map((marginObj) => {
      const cloneMarginObj = _.cloneDeep(marginObj);

      cloneMarginObj.manufacturer = manufacturer;

      return cloneMarginObj;
    });
  },
};