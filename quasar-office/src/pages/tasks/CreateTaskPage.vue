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
    param: false,
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
  form.value.name.error = ''
  form.value.value.error = ''
  form.value.status.error = ''
}
</script>

<template>
  <q-page class="q-pa-none">
    <div class="page-container">
      <div class="body-panel">
        <div class="scrollable-content full-height column">
          <q-card class="column q-pa-md full-width col">
            <q-form class="q-gutter-md col column no-wrap">
              <q-input
                v-model="form.name.param"
                :label="$t('Task')"
                :hint="$t('Task name')"
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
          @click="submit"
          :loading="loading"
        />
      </div>
    </div>
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
