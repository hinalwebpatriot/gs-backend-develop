import {
  mapGetters,
} from 'vuex';

export default {
  computed: {
    ...mapGetters([
      'translation',
    ]),
  },

  methods: {
    getTranslation(path) {
      return _.get(this.translation, path, '');
    },
  },
};