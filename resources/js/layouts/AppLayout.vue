<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Sidebar from '../components/Sidebar.vue'

// PAGE & NAVIGATION
const page = usePage()
const current = computed(() => page.url)
const isActive = (path: string) => current.value === path

// base styles
const basePill =
  'inline-block rounded-full px-4 py-1.5 text-lg font-medium transition ' +
  'focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60'

const activePill   = 'bg-white/25 text-white'
const inactivePill = 'text-white/90 hover:bg-white/15 hover:text-white'

</script>

<template>
  <div class="min-h-screen flex bg-gray-800 dark:bg-gray-900 text-gray-300 dark:text-gray-200 transition-colors duration-500">

    <!-- Left sidebar -->
    <Sidebar />

    <!-- Right side -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <header class="flex justify-between items-center px-16 py-7 bg-[#0b3d91] border-b-4 border-[#005ea5] transition-colors duration-500">

        <!-- Logo -->
        <div class="flex items-center space-x-5">
          <Link href="/" class="transition hover:opacity-90 active:opacity-80">
            <img src="/images/CTFcrop.png" alt="Logo" class="h-14" />
          </Link>
        </div>

        <!-- Right-side controls -->
        <div class="flex items-center gap-4">

          <!-- Register -->
          <Link
            v-if="!$page.props.auth.user"
            href="/register"
            :class="[basePill, isActive('/register') ? activePill : inactivePill]"
          >
            Register
          </Link>

          <!-- Login -->
          <Link
            v-if="!$page.props.auth.user"
            href="/login"
            :class="[basePill, isActive('/login') ? activePill : inactivePill]"
          >
            Login
          </Link>

        </div>
      </header>

      <!-- Page content -->
      <main class="flex-grow max-w-5xl mx-auto w-full px-4 py-10 transition-colors duration-500">
        <slot />
      </main>

      <!-- Footer -->
      <footer class="text-center text-gray-500 py-3 bg-gray-900 border-t border-gray-700 transition-colors duration-500">
        Powered by Laravel & Tailwind
      </footer>

    </div>
  </div>
</template>

<style>
/* Smooth transition between light/dark */
html {
  transition: background-color 0.4s ease, color 0.4s ease;
}

/* For icon rotation */
.theme-toggle span {
  display: inline-block;
}
</style>
