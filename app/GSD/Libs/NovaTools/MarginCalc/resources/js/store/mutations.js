import {
  LOAD_DATA
} from './mutation-types';

export default {
  [LOAD_DATA] (state, payload) {
    state[payload.loadedKey] = payload.loadedData;
  }
};