<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useQuasar } from 'quasar'
const $q = useQuasar()

const form = ref({
  error: '',
  loading: false,
  name: {
    param: '',
    error: '',
  },
  value: {
    param: '',
    error: '',
  },
  status: {
    param: 0,
    error: '',
  },
})

const loading = ref(false)
const router = useRouter()

const submit = async () => {
  clearForm()

  try {
    await axios.post(
      `${window.mt_office_rest.root}mt-office/v1/tasks`,
      {
        name: form.value.name.param,
        value: form.value.value.param,
        status: form.value.status.param,
      },
      {
        headers: {
          'X-WP-Nonce': window.mt_office_rest.nonce,
        },
      },
    )

    router.push({ name: 'tasks' })
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
    form.value.loading = false
  }
}

const clearForm = () => {
  form.value.error = ''
  form.value.loading = false
  form.value.name.param = ''
  form.value.name.error = ''
  form.value.value.param = ''
  form.value.value.error = ''
  form.value.status.param = 0
  form.value.status.error = ''
}
</script>

<template>
  <q-page class="q-pa-none">
    <div class="page-container">
      <div class="body-panel">
        <div class="scrollable-content">
          <div class="column flex-grow flex-center">
            <q-card class="q-pa-md full-width">
              <q-form class="q-gutter-md">
                <q-input
                  v-model="name"
                  :label="$t('Task')"
                  :hint="$t('Task name')"
                  autofocus
                  :error="form.name.error !== ''"
                  :error-message="form.name.error"
                />
                <q-input
                  v-model="value"
                  label="Стойност"
                  hint="sdfgsdf"
                  class="q-mb-md"
                  type="textarea"
                />
                <q-select
                  v-model="status"
                  :options="[
                    { label: 'Неактивна', value: 0 },
                    { label: 'Активна', value: 1 },
                  ]"
                  label="Статус"
                  hint="szaxdgfsdf"
                  emit-value
                  map-options
                />
                <div v-if="error" class="text-negative q-mt-sm">{{ error }}</div>
              </q-form>
            </q-card>
          </div>
        </div>
      </div>

      <div class="footer-panel">
        <q-btn color="primary" flat label="Tasks" icon="mdi-menu-left" @click="router.back()" />

        <q-btn
          color="primary"
          :label="$t('Save')"
          icon="mdi-file-document-plus-outline"
          @click="submit"
          :loading="loading"
        />
      </div>
    </div>
  </q-page>
</template>
