<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useQuasar } from 'quasar'
import { useI18n } from 'vue-i18n'

const $q = useQuasar()
const { t } = useI18n()
const rows = ref([])

const columns = [
  {
    name: 'id',
    required: true,
    label: t('ID'),
    align: 'left',
    field: 'id',
    style: 'width: 80px;',
    sortable: true,
  },
  {
    name: 'name',
    label: t('Task name'),
    align: 'left',
    field: 'name',
    sortable: true,
  },
  {
    name: 'status',
    label: t('Status'),
    align: 'left',
    field: 'status',
    style: 'width: 120px;',
    sortable: true,
  },
  {
    name: 'actions',
    label: t('Actions'),
    align: 'center',
    field: 'actions',
    sortable: false,
  },
]

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

onMounted(fetchTasks)

watch(pagination, () => {
  fetchTasks()
})

async function fetchTasks() {
  try {
    const response = await axios.get(`${window.mt_office_rest.root}mt-office/v1/tasks`, {
      headers: {
        'X-WP-Nonce': window.mt_office_rest.nonce,
      },
      params: {
        page: pagination.value.page,
        per_page: pagination.value.rowsPerPage,
      },
    })
    rows.value = response.data.data
    pagination.value.rowsNumber = response.data.total
  } catch (err) {
    $q.notify({
      message: err.response?.data?.message,
      icon: 'mdi-alert-circle-outline',
      type: 'negative',
    })
  }
}

async function deleteTask(taskId) {
  try {
    const response = await axios.delete(
      `${window.mt_office_rest.root}mt-office/v1/tasks/${taskId}`,
      {
        headers: {
          'X-WP-Nonce': window.mt_office_rest.nonce,
        },
      },
    )
    if (response.data.success) {
      $q.notify({ type: 'positive', message: response.data?.message })
      await fetchTasks()
    }
  } catch (err) {
    $q.notify({
      message: err.response?.data?.message,
      icon: 'mdi-alert-circle-outline',
      type: 'negative',
    })
  }
}

const confirmDelete = (taskId) => {
  $q.dialog({
    title: t('Confirm'),
    message: t('Do you want to delete the task?'),
    persistent: true,
    ok: {
      label: t('Yes'),
      color: 'primary',
    },
    cancel: {
      label: t('Cancel'),
      color: 'grey-1',
      textColor: 'grey-10',
      flat: true,
    },
  })
    .onOk(() => {
      deleteTask(taskId)
    })
    .onCancel(() => {})
    .onDismiss(() => {})
}

async function updateTask(task) {
  try {
    const response = await axios.put(
      `${window.mt_office_rest.root}mt-office/v1/tasks/${task.id}`,
      {
        name: task.name,
        value: task.value,
        status: parseInt(task.status) === 0 ? true : false,
      },
      {
        headers: {
          'X-WP-Nonce': window.mt_office_rest.nonce,
        },
      },
    )
    if (response.data.success) {
      $q.notify({ type: 'positive', message: response.data?.message })
      await fetchTasks()
    }
  } catch (err) {
    $q.notify({
      message: err.response?.data?.message,
      icon: 'mdi-alert-circle-outline',
      type: 'negative',
    })
  }
}
</script>

<template>
  <q-page class="q-pa-none">
    <div class="page-container">
      <div class="body-panel">
        <div class="scrollable-content">
          <q-table
            :title="$t('Tasks')"
            :rows="rows"
            :columns="columns"
            row-key="id"
            v-model:pagination="pagination"
          >
            <template v-slot:body-cell-actions="props">
              <q-td align="center" style="width: 120px">
                <q-btn
                  icon="mdi-check"
                  color="secondary"
                  :title="$t('Task status')"
                  dense
                  flat
                  rounded
                  @click.prevent="updateTask(props.row)"
                />
                <q-btn
                  icon="mdi-pencil-outline"
                  color="primary"
                  title="Промяна на задача"
                  dense
                  flat
                  rounded
                  :to="{ name: 'task-edit', params: { id: props.row.id } }"
                />
                <q-btn
                  icon="mdi-delete-outline"
                  color="negative"
                  title="Изтриване на продукт"
                  dense
                  flat
                  rounded
                  @click.prevent="confirmDelete(props.row.id)"
                />
              </q-td>
            </template>
          </q-table>
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
