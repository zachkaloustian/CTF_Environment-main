<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';
import { Users, UserPlus, Search } from 'lucide-vue-next';

defineOptions({
  layout: AppLayout,
});

interface Props {
  teams: {
    data: any[];
    links: any[];
    meta: {
      current_page: number;
      last_page: number;
    };
  };
  filters: {
    search?: string;
  };
  canCreateTeam: boolean;
}

const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || '');

const filteredTeams = computed(() => {
  if (!props.teams || !props.teams.data) return [];
  if (!searchQuery.value) return props.teams.data;

  return props.teams.data.filter((team: any) =>
    team.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    team.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const searchTeams = () => {
  router.get('/teams', { search: searchQuery.value }, {
    preserveState: true,
    replace: true,
  });
};

const joinTeam = (joinCode: string) => {
  router.post('/teams/join', { join_code: joinCode }, {
    onSuccess: () => {
      window.location.reload();
    },
  });
};
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-white">Teams</h1>
          <p class="mt-2 text-gray-400">
            Join a team or create your own to compete together
          </p>
        </div>
        <button
          v-if="props.canCreateTeam"
          @click="$inertia.visit('/teams/create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-pointer"
        >
          <UserPlus class="w-4 h-4" />
          Create Team
        </button>
      </div>
    </div>

    <!-- Search -->
    <div class="mb-6">
      <div class="flex gap-4">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            name="search"
            placeholder="Search teams..."
            class="max-w-md px-3 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            @keyup.enter="searchTeams"
          />
        </div>
        <button @click="searchTeams" class="px-4 py-2 border border-gray-600 rounded-lg hover:bg-gray-600 text-white flex items-center gap-2 cursor-pointer">
          <Search class="w-4 h-4" />
          Search
        </button>
      </div>
    </div>

    <!-- Teams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="team in filteredTeams"
        :key="team.id"
        class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer"
        @click="$inertia.visit(`/teams/${team.id}`)"
      >
        <div class="flex justify-between items-start mb-4">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-white">{{ team.name }}</h3>
            <p class="text-sm text-gray-400 mt-1">
              Captain: {{ team.captain?.name || 'Unknown' }}
            </p>
          </div>
          <span class="bg-gray-700 text-gray-200 px-2 py-1 rounded text-sm">
            {{ team.members_count }}/{{ team.max_size }}
          </span>
        </div>

        <p v-if="team.description" class="text-sm text-gray-400 mb-4">
          {{ team.description }}
        </p>

        <div class="flex items-center justify-between">
          <div class="flex items-center text-sm text-gray-400">
            <Users class="w-4 h-4 mr-1" />
            {{ team.members_count }} member{{ team.members_count !== 1 ? 's' : '' }}
          </div>

          <button
            v-if="props.canCreateTeam && team.members_count < team.max_size"
            class="px-3 py-1 border border-gray-600 rounded text-sm hover:bg-gray-700 text-gray-200"
            @click.stop="joinTeam(team.join_code)"
          >
            Join Team
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredTeams.length === 0" class="text-center py-12">
      <Users class="w-12 h-12 text-gray-400 mx-auto mb-4" />
      <h3 class="text-lg font-medium text-white mb-2">No teams found</h3>
      <p class="text-gray-400 mb-4">
        {{ searchQuery ? 'Try adjusting your search terms.' : 'Be the first to create a team!' }}
      </p>
      <button
        v-if="props.canCreateTeam"
        @click="$inertia.visit('/teams/create')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-pointer"
      >
        <UserPlus class="w-4 h-4" />
        Create Your Team
      </button>
    </div>

    <!-- Pagination -->
    <div v-if="props.teams?.meta?.last_page > 1" class="mt-8 flex justify-center">
      <div class="flex gap-2">
        <button
          v-for="link in props.teams.links"
          :key="link.label"
          :class="[
            'px-3 py-2 border rounded',
            link.active ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 hover:bg-gray-50',
            { 'opacity-50 cursor-not-allowed': !link.url }
          ]"
          :disabled="!link.url"
          @click="link.url && $inertia.visit(link.url)"
          v-html="link.label"
        />
      </div>
    </div>
  </div>
</template>