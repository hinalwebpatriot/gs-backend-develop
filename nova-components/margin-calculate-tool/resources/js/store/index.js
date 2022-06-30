import state from './state.json';
import actions from './actions';
import getters from './getters';
import mutations from './mutations';

export default {
  strict: process.env.NODE_ENV !== 'production',
  state,
  getters,
  actions,
  mutations,
};