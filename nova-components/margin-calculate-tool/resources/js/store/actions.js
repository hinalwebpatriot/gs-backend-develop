import {
  API_LANG_URL,
  API_MANUFACTURER_URL,
  API_COLOR_URL,
  API_CLARITY_URL,
  API_MARGIN_URL,
  API_MARGIN_SYNC_URL,
  API_PARAMS,
} from '../routes';
import {
  UPDATE_BASE_LOAD_STATE,
  UPDATE_SELECTED_MARGINS,
  ADD_ERROR,
  MARGINS_ARE_SYNCING,
  SYNC_SELECTED_MARGINS_FROM_DEFAULT,
  UPDATE_DEFAULT_MARGIN,
} from './mutation-types';

export default {
  loadTranslations({commit}) {
    window.axios.get(API_LANG_URL)
      .then(response => commit(UPDATE_BASE_LOAD_STATE, {
        loadedKey: 'translation',
        loadedData: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  loadParams({commit}) {
    window.axios.get(API_PARAMS)
      .then(response => commit(UPDATE_BASE_LOAD_STATE, {
        loadedKey: 'appParams',
        loadedData: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  loadManufacturers({commit}) {
    window.axios.get(API_MANUFACTURER_URL)
      .then(response => commit(UPDATE_BASE_LOAD_STATE, {
        loadedKey: 'manufacturers',
        loadedData: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  loadColors({commit}) {
    window.axios.get(API_COLOR_URL)
      .then(response => commit(UPDATE_BASE_LOAD_STATE, {
        loadedKey: 'colors',
        loadedData: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  loadClarities({commit}) {
    window.axios.get(API_CLARITY_URL)
      .then(response => commit(UPDATE_BASE_LOAD_STATE, {
        loadedKey: 'clarities',
        loadedData: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  loadDefaultMargins({commit}) {
    window.axios.get(API_MARGIN_URL.replace(':manufacturer', ''))
      .then(response => {
        commit(UPDATE_BASE_LOAD_STATE, {
          loadedKey: 'defaultMargins',
          loadedData: response.data,
        });


      })
      .catch(error => commit(ADD_ERROR, error));
  },

  loadMargins({commit}, manufacturer) {
    window.axios.get(API_MARGIN_URL.replace(':manufacturer', manufacturer || ''))
      .then(response => commit(UPDATE_SELECTED_MARGINS, {
        margins: response.data,
      }))
      .catch(error => commit(ADD_ERROR, error));
  },

  saveMarginOnServer({commit}, {marginObj, callback}) {
    window.axios.post(
      API_MARGIN_URL.replace(':manufacturer', marginObj.manufacturer || ''),
      marginObj
    )
      .then((response) => {
        if (!marginObj.manufacturer) {
          commit(UPDATE_DEFAULT_MARGIN, {marginObj});
        }

        if (typeof callback === 'function') {
          callback(response);
        }
      })
      .catch((error) => {
        commit(ADD_ERROR, error);
      });
  },

  syncMarginsWithDefault({commit}, manufacturer) {
    commit(MARGINS_ARE_SYNCING, true);

    const syncApiUrl = API_MARGIN_SYNC_URL.replace(':manufacturer', manufacturer);

    window.axios.post(syncApiUrl)
      .then(() => {
        commit(SYNC_SELECTED_MARGINS_FROM_DEFAULT);
        commit(MARGINS_ARE_SYNCING, false);
      })
      .catch((error) => {
        commit(ADD_ERROR, error);
        commit(MARGINS_ARE_SYNCING, false);
      });
  },
};