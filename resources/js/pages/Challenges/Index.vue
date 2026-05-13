<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

const page = usePage();

const props = defineProps<{
  challenges: Array<{
    id: string;
    title: string;
    description: string;
    difficulty: "easy" | "medium" | "hard";
    points: number;
    category: string;
  }>;
  selectedCategory?: string | null;
  filters?: {
    search: string | null;
    difficulty: string | null;
    sort: string;
    direction: string;
  };
}>();

// Reactive filters (sync with URL)
const searchQuery = ref(props.filters?.search || "");
const selectedDifficulty = ref(props.filters?.difficulty || "");
const selectedCategory = ref(props.selectedCategory || "");
const sortBy = ref(props.filters?.sort || "title");
const sortDirection = ref(props.filters?.direction || "asc");

// Update URL when filters change
watch([searchQuery, selectedDifficulty, selectedCategory, sortBy, sortDirection], () => {
  const params: Record<string, any> = {};
  if (searchQuery.value) params.search = searchQuery.value;
  if (selectedDifficulty.value) params.difficulty = selectedDifficulty.value;
  if (selectedCategory.value) params.category = selectedCategory.value;
  params.sort = sortBy.value;
  params.direction = sortDirection.value;
  router.get("/challenges", params, { preserveState: true });
});

const difficultyColor: Record<"easy" | "medium" | "hard", string> = {
  easy: "text-green-400 border-green-400",
  medium: "text-yellow-400 border-yellow-400",
  hard: "text-red-400 border-red-400",
};

// Unique lists
const categories = computed(() => {
  const all = props.challenges.map(c => c.category);
  return [...new Set(all)];
});

const difficulties = ["easy", "medium", "hard"];

// Filtered challenges (client-side fallback, but mainly server-side)
const filteredChallenges = computed(() => {
  let filtered = props.challenges;

  if (selectedCategory.value && selectedCategory.value !== "All") {
    filtered = filtered.filter(c => c.category === selectedCategory.value);
  }

  if (selectedDifficulty.value) {
    filtered = filtered.filter(c => c.difficulty === selectedDifficulty.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(c =>
      c.title.toLowerCase().includes(query) ||
      c.description.toLowerCase().includes(query)
    );
  }

  // Sort (client-side as backup)
  filtered.sort((a, b) => {
    let aVal, bVal;
    if (sortBy.value === "points") {
      aVal = a.points;
      bVal = b.points;
    } else if (sortBy.value === "difficulty") {
      const order = { easy: 1, medium: 2, hard: 3 };
      aVal = order[a.difficulty];
      bVal = order[b.difficulty];
    } else {
      aVal = a.title.toLowerCase();
      bVal = b.title.toLowerCase();
    }
    return sortDirection.value === "asc" ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
  });

  return filtered;
});
</script>

<template>
  <AppLayout>
    <div class="text-gray-200 p-6">

      <!-- Title + Create Button -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#075ae9]">Challenges</h1>

        <Link v-if="page.props.auth.user" href="/challenges/create"
          class="bg-[#075ae9] hover:bg-[#0a44b9] text-white px-4 py-2 rounded-lg shadow-md transition flex items-center gap-2">
          <span class="material-symbols-outlined text-xl">add_circle</span>
          Create Challenge
        </Link>
      </div>

      <!-- Filters -->
      <div class="flex gap-4 mb-6 flex-wrap items-center">

        <!-- Search -->
        <input v-model="searchQuery" type="text" placeholder="Search challenges..."
          class="px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm focus:border-blue-500" />

        <!-- Category -->
        <select v-model="selectedCategory" class="px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm">
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>

        <!-- Difficulty -->
        <select v-model="selectedDifficulty" class="px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm">
          <option value="">All Difficulties</option>
          <option v-for="diff in difficulties" :key="diff" :value="diff" class="capitalize">{{ diff }}</option>
        </select>

        <!-- Sort -->
        <select v-model="sortBy" class="px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm">
          <option value="title">Title</option>
          <option value="points">Points</option>
          <option value="difficulty">Difficulty</option>
        </select>

        <!-- Direction -->
        <button @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'"
          class="px-3 py-2 bg-gray-800 border border-gray-700 rounded text-sm">
          {{ sortDirection === 'asc' ? '↑' : '↓' }}
        </button>

      </div>

      <!-- Challenge Grid (unchanged) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="challenge in filteredChallenges" :key="challenge.id" class="bg-gray-900 border border-gray-700 rounded-xl p-5 shadow-lg 
                 hover:border-blue-500 hover:shadow-blue-500/20 transition cursor-pointer">
          <p class="text-sm text-gray-400 mb-1 capitalize">
            {{ challenge.category }}
          </p>

          <h2 class="text-xl font-semibold mb-3">
            {{ challenge.title }}
          </h2>

          <div class="flex items-center justify-between mb-4">
            <span class="px-2 py-1 text-xs rounded border capitalize" :class="difficultyColor[challenge.difficulty]">
              {{ challenge.difficulty }}
            </span>

            <span class="text-blue-300 font-semibold">
              {{ challenge.points }} pts
            </span>
          </div>

          <Link :href="`/challenges/${challenge.id}`" class="block text-center w-full bg-[#075ae9] hover:bg-[#0a44b9] 
                   text-white py-2 rounded-lg shadow-md transition">
            View Challenge
          </Link>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
