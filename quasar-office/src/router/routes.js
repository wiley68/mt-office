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
      {
        path: '/tasks/:id/edit',
        name: 'task-edit',
        component: () => import('pages/tasks/EditTaskPage.vue'),
        meta: {
          title: 'Task editing',
          icon: 'mdi-pencil',
        },
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
