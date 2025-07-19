<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const name = ref('')
const value = ref('')
const status = ref(0)

const loading = ref(false)
const error = ref('')
const router = useRouter()

const submit = async () => {
  error.value = ''
  loading.value = true

  try {
    await axios.post(
      `${window.mt_office_rest.root}mt-office/v1/tasks`,
      {
        name: name.value,
        value: value.value,
        status: status.value,
      },
      {
        headers: {
          'X-WP-Nonce': window.mt_office_rest.nonce,
        },
      },
    )

    router.push({ name: 'tasks' })
  } catch (err) {
    error.value = 'Грешка при запис.'
    console.error(err)
  } finally {
    loading.value = false
  }
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
                <q-input v-model="name" :label="$t('Task')" :hint="$t('Task name')" autofocus />
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
