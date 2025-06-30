<template>
  <div v-if="visible" class="overlay-loader">
    <q-spinner size="50px" color="orange-9" />
    <div class="text text-grey-10">Зареждане...</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { QSpinner } from 'quasar'

const visible = ref(false)

// Тези event-и идват от Inertia.js
document.addEventListener('inertia:start', () => {
  visible.value = true
})

document.addEventListener('inertia:finish', () => {
  visible.value = false
})
</script>

<style scoped>
.overlay-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 9999;

  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  font-size: 1.2rem;
  color: #ea580c;
}

.text {
  margin-top: 15px;
}
</style>
