<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Users } from 'lucide-vue-next'

// Props coming from DashboardController
const props = defineProps<{
  stats: {
    points: number
    rank: number
    solves: number
  },
  recentSolves: Array<{
    challenge: string
    points: number
    time: string
  }>,
  categories: Array<{
    name: string
    icon: string
  }>,
  pointsByCategory: Array<{
    category: string
    total_points: number
  }>
}>()
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout>
    <div class="p-6 text-gray-200">

      <!-- Title + Settings -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#075ae9]">Dashboard</h1>

        <div class="flex gap-3">
          <Link
            href="/teams"
            class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 border border-blue-500
                   text-white hover:bg-blue-700 hover:border-blue-400
                   transition shadow-lg"
          >
            <Users class="w-4 h-4" />
            <span class="font-medium">Teams</span>
          </Link>

          <Link
            href="/settings"
            class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 border border-gray-700
                   text-gray-200 hover:bg-gray-800 hover:border-blue-500 hover:text-white
                   transition shadow-lg"
          >
            <span class="material-symbols-outlined">settings</span>
            <span class="font-medium">Settings</span>
          </Link>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg hover:shadow-blue-900/20 transition">
          <h2 class="text-lg text-gray-400">Total Points</h2>
          <p class="text-4xl font-bold text-[#3fa9f5] mt-2">{{ props.stats.points }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg hover:shadow-blue-900/20 transition">
          <h2 class="text-lg text-gray-400">Rank</h2>
          <p class="text-4xl font-bold text-[#3fa9f5] mt-2">#{{ props.stats.rank }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg hover:shadow-blue-900/20 transition">
          <h2 class="text-lg text-gray-400">Challenges Solved</h2>
          <p class="text-4xl font-bold text-[#3fa9f5] mt-2">{{ props.stats.solves }}</p>
        </div>

      </div>

      <!-- NEW: Points Per Category -->
      <h2 class="text-xl font-semibold text-[#075ae9] mb-3">Points by Category</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
        <div
          v-if="props.pointsByCategory.length === 0"
          class="text-gray-400 text-sm col-span-full"
        >
          No solves yet.
        </div>

        <div
          v-for="row in props.pointsByCategory"
          :key="row.category"
          class="bg-gray-900 border border-gray-700 p-5 rounded-xl shadow-lg 
                 hover:bg-gray-800 hover:border-blue-500 hover:shadow-blue-500/20 transition"
        >
          <p class="text-lg font-semibold text-gray-200">{{ row.category }}</p>
          <p class="text-3xl font-bold text-blue-300 mt-2">{{ row.total_points }}</p>
        </div>
      </div>

      <!-- Categories -->
      <div
        v-for="cat in props.categories"
        :key="cat.name"
      >
        <Link
          :href="`/challenges?category=${encodeURIComponent(cat.name)}`"
          class="bg-gray-900 border border-gray-700 p-5 rounded-xl flex items-center gap-4 
            hover:bg-gray-800 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/20 
              transition cursor-pointer w-full"
        >
          <span class="material-symbols-outlined text-3xl text-blue-300">
            {{ cat.icon }}
          </span>
          <span class="text-lg">{{ cat.name }}</span>
        </Link>
      </div>


      <!-- Recent solves -->
      <h2 class="text-xl font-semibold text-[#075ae9] mb-3">Recent Solves</h2>

      <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg">
        <ul class="space-y-4">

          <li
            v-for="solve in props.recentSolves"
            :key="solve.challenge"
            class="flex justify-between border-b border-gray-700 pb-3"
          >
            <span class="font-medium">{{ solve.challenge }}</span>

            <div class="text-right">
              <p class="text-blue-300 font-semibold">+{{ solve.points }} pts</p>
              <p class="text-gray-400 text-sm">{{ solve.time }}</p>
            </div>
          </li>

        </ul>
      </div>

    </div>
  </AppLayout>
</template>
