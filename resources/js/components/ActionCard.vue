<template>
  <Card class="px-3 py-3">
    <div class="flex flex-col items-stretch justify-center">
      <h3 v-if="card.label" class="text-lg font-bold mb-2">{{ card.label }}</h3>
      <div class="space-y-2">
        <div
          v-for="(action, index) in card.actions"
          :key="action.url"
          :title="action.label"
        >
          <button
            :onclick="!isLoading[index] ? onClick[index] : undefined"
            :disabled="isLoading[index]"
            class="w-full text-center bg-primary-500 hover:bg-primary-600 px-2 py-1 rounded text-white"
            :class="{ 'opacity-50': isLoading[index] }"
          >
            {{ action.label }}
          </button>
        </div>
      </div>
    </div>
  </Card>
</template>

<script>
export default {
  props: {
    card: {
      type: {
        width: String,
        height: String,
        component: String,
        onlyOnDetail: Boolean,
        prefixComponent: Boolean,
        label: String,
        actions: [
          {
            label: String,
            url: String,
            method: String,
            headers: String,
          },
        ],
      },
      required: true,
    },

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  },

  data() {
    return {
      isLoading: [],
    };
  },

  computed: {
    onClick() {
      return this.card.actions.map((action, index) => async () => {
        this.isLoading[index] = true;
        Nova.info(
          `Operazione "${action.label}" in corso, potrebbe volerci un po' di tempo...`
        );

        let success = false;
        try {
          switch (action.method.toLowerCase().trim()) {
            case 'get':
              await Nova.request().get(action.url);
              break;
            case 'head':
              await Nova.request().head(action.url);
              break;
            case 'post':
              await Nova.request().post(action.url);
              break;
            case 'put':
              await Nova.request().put(action.url);
              break;
            case 'patch':
              await Nova.request().patch(action.url);
              break;
            case 'delete':
              await Nova.request().delete(action.url);
              break;
            default:
              throw new Error(`Unknown HTTP method "${action.method}"`);
          }
          success = true;
        } catch (e) {
          Nova.error(e.toLocaleString());
        }
        this.isLoading[index] = false;

        if (success) {
          Nova.success(`Operazione "${action.label}" eseguita con successo!`);
        }
      });
    },
  },

  mounted() {
    this.isLoading = this.card.actions.map(() => false);
  },
};
</script>
