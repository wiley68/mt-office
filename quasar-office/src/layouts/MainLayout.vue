<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const leftDrawerOpen = ref(false)
const wpSiteName = ref('')
const wpPluginName = ref('')
const wpPluginVersion = ref('')
const wpUser = ref({
  id: 0,
  name: '',
  email: '',
})
const wpWordPressVersion = ref('')

const getMetaByRouteName = (name) => {
  const route = router.getRoutes().find((r) => r.name === name)
  return route?.meta || {}
}

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

onMounted(() => {
  if (typeof window.mt_office_rest !== 'undefined') {
    wpSiteName.value = window.mt_office_rest.siteName
    wpPluginName.value = window.mt_office_rest.pluginName
    wpPluginVersion.value = window.mt_office_rest.pluginVersion
    wpUser.value.id = window.mt_office_rest.user.id
    wpUser.value.name = window.mt_office_rest.user.name
    wpUser.value.email = window.mt_office_rest.user.email
    wpWordPressVersion.value = window.mt_office_rest.wordPressVersion
  }
})
</script>

<template>
  <q-layout view="hHh lpR fFf">
    <q-header bordered class="bg-primary text-white select-none">
      <q-toolbar>
        <q-btn dense flat round icon="mdi-menu" @click="toggleLeftDrawer" class="q-mr-sm" />

        <q-separator dark vertical inset />

        <q-toolbar-title style="flex: none">{{ wpSiteName }}</q-toolbar-title>

        <q-separator dark vertical inset />

        <q-toolbar-title>
          <div class="row items-center">
            <q-icon :name="route.meta.icon" size="md" class="q-mr-sm"></q-icon>
            {{ wpPluginName }} - {{ $t(route.meta.title) }}
          </div>
        </q-toolbar-title>

        <q-separator dark vertical inset />

        <q-btn-dropdown stretch flat :label="wpUser.name" class="q-ml-auto">
          <q-list style="min-width: 100px">
            <q-item>
              <q-item-section class="select-none">{{ wpUser.email }}</q-item-section>
            </q-item>
            <q-separator />
            <q-item
              clickable
              tag="a"
              href="/wp-admin/admin.php?page=mt-office-overview"
              class="text-negative"
              v-close-popup
            >
              <q-item-section avatar>
                <q-icon color="negative" name="mdi-close" />
              </q-item-section>
              <q-item-section>{{ $t('Exit') }}</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" behavior="desktop" bordered>
      <q-list class="full-height flex column">
        <q-item
          clickable
          :to="{ name: 'dashboard' }"
          v-close-popup
          :active="route.name === 'dashboard'"
          class="text-primary"
          active-class="bg-blue-1"
        >
          <q-item-section avatar>
            <q-icon color="primary" name="mdi-view-dashboard-outline" />
          </q-item-section>
          <q-item-section>{{ $t(getMetaByRouteName('dashboard').title) }}</q-item-section>
        </q-item>

        <q-separator />

        <q-item
          clickable
          :to="{ name: 'tasks' }"
          v-close-popup
          :active="
            route.name === 'tasks' || route.name === 'task-create' || route.name === 'task-edit'
          "
          class="text-primary"
          active-class="bg-blue-1"
        >
          <q-item-section avatar>
            <q-icon color="primary" name="mdi-calendar-check" />
          </q-item-section>
          <q-item-section>{{ $t('Tasks') }}</q-item-section>
        </q-item>

        <q-separator />

        <q-space />

        <q-separator />

        <q-item
          clickable
          tag="a"
          href="/wp-admin/admin.php?page=mt-office-overview"
          class="text-negative"
        >
          <q-item-section avatar>
            <q-icon color="negative" name="close" />
          </q-item-section>
          <q-item-section>{{ $t('Exit') }}</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view></router-view>
    </q-page-container>

    <q-footer bordered class="bg-grey-2 text-grey-10 q-custom-toolbar">
      <q-toolbar class="select-none q-custom-toolbar">
        <q-toolbar-title class="text-left text-subtitle1 text-title"
          >{{ wpPluginName }} - {{ wpPluginVersion }}</q-toolbar-title
        >
        <q-separator vertical />
        <q-toolbar-title class="text-left text-subtitle1 text-title"
          >WP - {{ wpWordPressVersion }}</q-toolbar-title
        >
        <q-separator vertical />
        <q-toolbar-title class="text-right text-subtitle1 text-grey-8">{{
          wpUser.email
        }}</q-toolbar-title>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<style>
.page-container {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 82px);
  overflow-y: auto;
}

.q-custom-toolbar {
  min-height: 30px !important;
}

.text-title {
  max-width: max-content;
  white-space: nowrap;
}

.body-panel {
  flex: 1;
  overflow-y: auto;
  border-bottom: 1px solid #e0e0e0;
}

.header-panel {
  display: flex;
  align-items: center;
  width: 100%;
  background-color: #ffffff;
  border-bottom: 1px solid #e0e0e0;
}

.footer-panel {
  height: 48px;
  display: flex;
  gap: 5px;
  align-items: center;
  width: 100%;
  background-color: #ffffff;
  padding-left: 4px;
  padding-right: 4px;
}

.scrollable-content {
  padding: 16px;
}

.q-field__messages > div {
  color: #1976d2;
}

.q-field__messages > div[role='alert'] {
  color: #c10015;
}

.select-none {
  user-select: none;
}
</style>
