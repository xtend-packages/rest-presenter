import { createScalarApiClient } from '@scalar/api-client-modal'

const { open } = await createScalarApiClient(document.getElementById('apiClient'), {
  spec: {
    url: 'https://cdn.jsdelivr.net/npm/@scalar/galaxy/dist/latest.json',
  },
})

open()
