import translations from '../mixins/translations';

export default {
  mixins: [
    translations,
  ],

  created() {
    this.$store.watch(
      () => this.$store.getters.marginsAreSyncing,
      (marginsAreSyncing) => {
        if (!marginsAreSyncing) {
          return this.$swal.close();

          return;
        }

        this.$swal({
          toast: true,
          type: 'info',
          position: 'bottom-end',
          text: this.getTranslation('margins_are_syncing'),
          showConfirmButton: false,
        });
      }
    );
  },
};