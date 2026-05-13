<template>
  <AppShell>
    <AppContent>
      <div class="max-w-4xl mx-auto">
        <Heading>My Badges</Heading>

        <div v-if="badges.length === 0" class="text-center py-12">
          <p class="text-gray-500">You haven't earned any badges yet. Start solving challenges to unlock them!</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="badge in badges"
            :key="badge.id"
            class="bg-white rounded-lg shadow-sm border p-6 text-center"
          >
            <div class="text-4xl mb-4">
              <i :class="`fas fa-${badge.icon}`"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ badge.name }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ badge.description }}</p>
            <p class="text-xs text-gray-500 mt-4">
              Awarded on {{ formatDate(badge.awarded_at) }}
            </p>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import AppShell from '@/components/AppShell.vue';
import AppContent from '@/components/AppContent.vue';
import Heading from '@/components/Heading.vue';

defineProps({
  badges: Array,
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};
</script>