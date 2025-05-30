const routes = [
  {
    path: '/',
    component: () => import('layouts/MainAdmin.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/login', component: () => import('pages/LoginPage.vue') },
      { path: '/register', name: 'register', component: () => import('pages/RegisterPage.vue') },
      { path: '/art', component: () => import('pages/ArtPage.vue') },
      { path: '/art/:id', name: 'art', component: () => import('pages/ArtDetailPage.vue') },
      { path: '/productos', component: () => import('pages/ProductsPage.vue') },
      {
        path: '/productos/:id',
        name: 'product',
        component: () => import('pages/ProductDetailPage.vue'),
      },
      { path: '/carrito', component: () => import('pages/CartPage.vue') },
      { path: '/perfil', name: 'perfil', component: () => import('pages/PerfilPage.vue') },
      {
        path: '/pago',
        name: 'payment-page',
        component: () => import('pages/PaymentPage.vue'),
        props: true,
      },
      {
        path: '/gracias',
        name: 'gracias-page',
        component: () => import('pages/Gracias.vue'),
      },
      {
        path: '/cancelado',
        name: 'cancelado-page',
        component: () => import('pages/Cancelado.vue'),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
