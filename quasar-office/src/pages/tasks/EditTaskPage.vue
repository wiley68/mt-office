<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const $q = useQuasar()
const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const taskId = route.params.id

const form = ref({
  error: '',
  loading: true,
  saving: false,
  name: {
    param: '',
    error: '',
  },
  value: {
    param: '',
    error: '',
  },
  status: {
    param: false,
    error: '',
  },
})

async function fetchTask() {
  try {
    const response = await axios.get(`${window.mt_office_rest.root}mt-office/v1/tasks/${taskId}`, {
      headers: { 'X-WP-Nonce': window.mt_office_rest.nonce },
    })
    form.value.name.param = response.data.name || ''
    form.value.value.param = response.data.value || ''
    form.value.status.param = !!response.data.status
  } catch (err) {
    if (err.response.data.code === 'name') {
      form.value.name.error = err.response.data.message
    }
    $q.notify({
      message: err.response.data.message,
      icon: 'mdi-alert-circle-outline',
      type: 'negative',
    })
    router.push({ name: 'tasks' })
  } finally {
    form.value.loading = false
  }
}

async function saveTask() {
  form.value.saving = true
  try {
    await axios.put(
      `${window.mt_office_rest.root}mt-office/v1/tasks/${taskId}`,
      {
        name: form.value.name.param,
        value: form.value.value.param,
        status: form.value.status.param,
      },
      {
        headers: { 'X-WP-Nonce': window.mt_office_rest.nonce },
      },
    )
    $q.notify({ type: 'positive', message: t('Task updated successfully') })
  } catch (err) {
    if (err.response.data.code === 'name') {
      form.value.name.error = err.response.data.message
    }
    $q.notify({
      message: err.response.data.message,
      icon: 'mdi-alert-circle-outline',
      type: 'negative',
    })
  } finally {
    form.value.saving = false
  }
}

onMounted(fetchTask)
</script>

<template>
  <q-page class="q-pa-none">
    <div class="page-container">
      <div class="body-panel">
        <div class="scrollable-content full-height column">
          <q-card class="column q-pa-md full-width col" v-if="!form.loading">
            <q-form class="q-gutter-md col column no-wrap">
              <q-input
                v-model="form.name.param"
                :label="$t('Task')"
                :hint="$t('Task name')"
                :rules="[(val) => !!val || $t('Required field')]"
                autofocus
                :error="form.name.error !== ''"
                :error-message="form.name.error"
              />

              <div class="col textarea-wrapper">
                <q-input
                  v-model="form.value.param"
                  :label="$t('Desription')"
                  :hint="$t('Task description')"
                  class="q-mb-md"
                  autogrow
                  type="textarea"
                />
              </div>

              <q-toggle
                v-model="form.status.param"
                :label="form.status.param === false ? $t('Active task') : $t('Completed task')"
              />
            </q-form>
          </q-card>
        </div>
      </div>

      <div class="footer-panel">
        <q-btn
          color="primary"
          flat
          :label="$t('Tasks')"
          icon="mdi-menu-left"
          @click="router.back()"
        />

        <q-btn
          color="primary"
          :label="$t('Save')"
          icon="mdi-file-document-plus-outline"
          @click="saveTask"
          :loading="form.saving"
        />
      </div>
    </div>
    <q-inner-loading :showing="form.loading" />
  </q-page>
</template>

<style scoped>
.textarea-wrapper {
  display: flex;
  flex-direction: column;
  height: 100%;
  overflow-y: auto;
}
</style>
