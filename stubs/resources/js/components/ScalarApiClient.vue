<script setup lang="ts">
import { ApiClient, useRequestStore } from '@scalar/api-client'
import { ApiReference } from '@scalar/api-reference'
import { ref } from 'vue'

const { setActiveRequest } = useRequestStore()

const endpointData = document.getElementById('apiClient').dataset.endpoint
if (!endpointData) {
    throw new Error('No endpoint data found')
}

const endpoint = JSON.parse(endpointData);

setActiveRequest({
  url: endpoint.base_url,
  type: endpoint.type,
  path: endpoint.uri,
})

const config = ref({
  proxyUrl: import.meta.env.VITE_REQUEST_PROXY_URL,
  readOnly: false,
})
</script>
<template>
  <div class="client-page">
    <ApiClient v-bind="config" />
    <ApiReference class="hidden" />
  </div>
</template>
