<script setup>
import { ref } from 'vue'

defineProps({
  title: String,
  icon: String,
})

const leftDrawerOpen = ref(false)
const isExpandedNomenklature = ref(false)

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>

<template>
  <q-layout view="hHh lpR fFf">
    <q-header bordered class="bg-primary text-white select-none">
      <q-toolbar>
        <q-btn dense flat round icon="mdi-menu" @click="toggleLeftDrawer" class="q-mr-sm" />

        <q-separator dark vertical inset />

        <q-toolbar-title style="flex: none">
          <q-icon name="mdi-at" size="md"></q-icon>
          Авалон
        </q-toolbar-title>

        <q-separator dark vertical inset />

        <q-toolbar-title>
          <div class="row items-center">
            <q-icon :name="icon" size="md" class="q-mr-sm"></q-icon>
            title
          </div>
        </q-toolbar-title>

        <q-separator dark vertical inset />

        <q-btn-dropdown stretch flat label="5555555">
          <q-list style="min-width: 100px">
            <q-item clickable v-close-popup>
              <q-item-section avatar>
                <q-icon color="negative" name="mdi-close" />
              </q-item-section>
              <q-item-section>Изход</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" behavior="desktop" bordered>
      <q-list class="full-height flex column">
        <q-item
          clickable
          v-close-popup
          :active="true"
          class="text-primary"
          active-class="bg-blue-1"
        >
          <q-item-section avatar>
            <q-icon color="primary" name="mdi-view-dashboard-outline" />
          </q-item-section>
          <q-item-section>Табло</q-item-section>
        </q-item>

        <q-separator />

        <q-expansion-item
          v-model="isExpandedNomenklature"
          group="nomenklature"
          icon="mdi-folder-table-outline"
          label="Номенклатури"
          expand-icon-class="text-primary"
          header-class="text-primary"
        >
          <q-item clickable class="text-secondary" active-class="bg-blue-1" :active="true">
            <q-item-section avatar>
              <q-icon color="secondary" name="mdi-home-city-outline" />
            </q-item-section>
            <q-item-section>Населени места</q-item-section>
          </q-item>
        </q-expansion-item>

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
          <q-item-section>Back to Overview</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container style="padding-top: 28px; padding-left: 300px; padding-bottom: 31px">
      <router-view></router-view>
    </q-page-container>

    <q-footer bordered class="bg-grey-2 text-grey-10 q-custom-toolbar">
      <q-toolbar class="select-none q-custom-toolbar">
        <q-toolbar-title class="text-left text-subtitle1 text-title">1111111111</q-toolbar-title>
        <q-separator vertical />
        <q-toolbar-title class="text-left text-subtitle1 text-title">222222222222</q-toolbar-title>
        <q-separator vertical />
        <q-toolbar-title class="text-left text-subtitle1 text-title">333333333</q-toolbar-title>
        <q-separator vertical />
        <q-toolbar-title class="text-right text-subtitle1 text-grey-8">444444444</q-toolbar-title>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<style>
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
</style>
