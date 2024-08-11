<script setup lang="ts">
import {
  ApiReference,
  type ReferenceConfiguration,
} from '@scalar/api-reference'
import content from '@scalar/galaxy/latest.yaml?raw'
import { reactive } from 'vue'

const configuration = reactive<ReferenceConfiguration>({
  proxy: import.meta.env.VITE_REQUEST_PROXY_URL,
  isEditable: true,
  spec: { content },
})
</script>
<template>
  <main>
      <ApiReference :configuration="configuration" />
  </main>
</template>
