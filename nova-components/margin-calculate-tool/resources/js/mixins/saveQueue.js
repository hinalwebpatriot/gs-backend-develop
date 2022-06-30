import {
  mapMutations,
} from 'vuex';
import MarginQueue from '../lib/MarginQueue';
import {
  UPDATE_MARGIN_QUEUE,
} from '../store/mutation-types';

const saveQueue = new MarginQueue();

export default {
  created() {
    this.$store.watch(
      () => this.$store.getters.marginQueue,
      (marginQueue, prevMarginQueue) => {
        if (!marginQueue && prevMarginQueue) {
          this.saveQueue.clearMargins();
        }
      }
    );

    saveQueue.subscribeOn('change', (marginList) => {
      this.changingSize = marginList.size;
    });
  },

  data() {
    return {
      changingSize: 0,
      saveQueue,
    };
  },

  methods: {
    ...mapMutations({
      updateMarginQueue: UPDATE_MARGIN_QUEUE,
    }),

    saveChanges() {
      this.updateMarginQueue({
        queue: this.saveQueue.getMarginList().values(),
      });
    },
  }
};