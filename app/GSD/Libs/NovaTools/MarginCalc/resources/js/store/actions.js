import {
    API_INDEX_URL,
    API_MARGIN_URL
} from '../routes';

import {
    LOAD_DATA
} from './mutation-types';

export default {
    loadData({commit}) {
        window.axios.get(API_INDEX_URL)
            .then((response) => {
                commit(LOAD_DATA, {
                    loadedKey: 'currency',
                    loadedData: response.data.currency,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'gst',
                    loadedData: response.data.gst,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'caratRanges',
                    loadedData: response.data.caratRanges,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'colors',
                    loadedData: response.data.colors,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'clarities',
                    loadedData: response.data.clarities,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'increasePercent',
                    loadedData: response.data.increasePercent,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'soldPercent',
                    loadedData: response.data.soldPercent,
                });
                commit(LOAD_DATA, {
                    loadedKey: 'manufacturers',
                    loadedData: response.data.manufacturers,
                });
            })
        //.catch(error => commit(ADD_ERROR, error));
    },
    getMarginPercent({commit}, {range, colorId, clarityId, isRound, manId}) {
        window.axios.post(API_MARGIN_URL, {
            range: range,
            colorId: colorId,
            clarityId: clarityId,
            isRound: isRound,
            manId: manId
        })
            .then((response) => {
                commit(LOAD_DATA, {
                    loadedKey: 'margin',
                    loadedData: response.data.margin,
                });
            });
    }
};