<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Users, LayoutGrid, Trophy } from 'lucide-vue-next';
</script>

<template>
  <div class="w-64 bg-gray-900 text-white h-full flex flex-col">
    <!-- Header -->
    <div class="p-4 border-b border-gray-700">
      <Link href="/" class="block">
        <img src="/images/CTFcrop.png" alt="Logo" class="h-8" />
      </Link>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4">
      <ul class="space-y-2">
        <li>
          <Link
            href="/dashboard"
            class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors"
          >
            <LayoutGrid class="w-5 h-5 mr-3" />
            <span>Dashboard</span>
          </Link>
        </li>
        <li>
          <Link
            href="/teams"
            class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors"
          >
            <Users class="w-5 h-5 mr-3" />
            <span>Teams</span>
          </Link>
        </li>
        <li>
          <Link
            href="/badges"
            class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors"
          >
            <Trophy class="w-5 h-5 mr-3" />
            <span>Badges</span>
          </Link>
        </li>
      </ul>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700">
      <div class="text-sm text-gray-400">
        Powered by Laravel
      </div>
    </div>
  </div>
</template>
