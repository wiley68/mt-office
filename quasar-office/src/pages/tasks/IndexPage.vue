<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rows = ref([])
const columns = [
  { name: 'id', label: 'ID', align: 'left', field: 'id' },
  { name: 'name', label: 'Име', align: 'left', field: 'name' },
  { name: 'status', label: 'Статус', align: 'left', field: 'status' },
  { name: 'created_at', label: 'Създадена на', align: 'left', field: 'created_at' },
]

onMounted(async () => {
  try {
    const response = await axios.get(`${window.mt_office_rest.root}mt-office/v1/tasks`, {
      headers: {
        'X-WP-Nonce': window.mt_office_rest.nonce,
      },
    })
    rows.value = response.data
  } catch (error) {
    console.error('Error loading tasks:', error)
  }
})
</script>

<template>
  <q-page class="q-pa-none">
    <div class="page-container">
      <div class="body-panel">
        <div class="scrollable-content">
          <q-table title="Задачи" :rows="rows" :columns="columns" row-key="id" />
        </div>
      </div>
      <div class="footer-panel">
        <q-btn
          color="primary"
          flat
          label="Табло"
          icon="mdi-menu-left"
          :to="{ name: 'dashboard' }"
        />

        <q-btn
          color="primary"
          :label="$t('New task')"
          icon="mdi-file-document-plus-outline"
          :to="{ name: 'task-create' }"
        />
      </div>
    </div>
  </q-page>
</template>
