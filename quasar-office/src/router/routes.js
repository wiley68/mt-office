const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        name: 'dashboard',
        component: () => import('pages/IndexPage.vue'),
        meta: {
          title: 'Dashboard',
          icon: 'mdi-view-dashboard',
        },
      },
      {
        path: '/tasks',
        name: 'tasks',
        component: () => import('pages/tasks/IndexPage.vue'),
        meta: {
          title: 'Tasks',
          icon: 'mdi-calendar-check',
        },
      },
      {
        path: '/tasks/create',
        name: 'task-create',
        component: () => import('pages/tasks/CreateTaskPage.vue'),
        meta: { title: 'Create task', icon: 'mdi-plus' },
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
