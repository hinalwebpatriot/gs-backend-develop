Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'MarginCalc',
      path: '/MarginCalc',
      component: require('./components/Tool'),
    },
  ])
})
