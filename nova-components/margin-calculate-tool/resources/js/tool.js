import Vuex from 'vuex';
import VueSweetalert2 from 'vue-sweetalert2';
import VueNoty from 'vuejs-noty';

import Tool from './components/Tool.vue';

Nova.booting((Vue, router) => {
  Vue.config.devtools = process.env.NODE_ENV === 'development';
  Vue.use(Vuex);
  Vue.use(VueSweetalert2);
  Vue.use(VueNoty);

  router.addRoutes([
    {
      name: 'MarginCalculate',
      path: '/MarginCalculate',
      component: Tool,
    },
  ]);
});
