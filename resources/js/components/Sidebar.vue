<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

// state
const isOpen = ref(false)
const page = usePage()

const openSidebar = () => (isOpen.value = true)
const closeSidebar = () => (isOpen.value = false)

const isActive = (path: string) => page.url === path

// logout handler
const handleLogout = () => {
  router.post('/logout')
}

// ESC closes sidebar
const handleKey = (e: KeyboardEvent) => {
  if (e.key === 'Escape') closeSidebar()
}

onMounted(() => window.addEventListener('keydown', handleKey))
onBeforeUnmount(() => window.removeEventListener('keydown', handleKey))
</script>

<template>
  <!-- menu icon (Hover target) -->
  <div
    class="fixed top-3 left-3 z-[100] flex items-center justify-center 
           bg-[#0b3d91] h-10 w-10 rounded-md text-white shadow-md 
           hover:bg-[#0a2f6d] transition cursor-pointer"
    @mouseenter="openSidebar"
  >
    <span class="material-symbols-outlined text-2xl">menu</span>
  </div>

  <!-- hover strip to open -->
  <div
    @mouseenter="openSidebar"
    class="fixed left-0 top-0 h-full w-8 z-[90]"
  ></div>

  <!-- dark overlay -->
  <div
    v-if="isOpen"
    @click="closeSidebar"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[80]"
  ></div>

  <!-- sidebar drawer -->
  <aside
    @mouseleave="closeSidebar"
    :class="[
      'fixed top-0 left-0 h-full w-64 bg-[#0b3d91] text-white shadow-xl z-[95] p-6 flex flex-col',
      'transition-transform duration-300 ease-out',
      isOpen ? 'translate-x-0' : '-translate-x-full'
    ]"
  >
    <!-- logo -->
    <div class="mb-10 pt-6">
      <img src="/images/CTFcrop.png" class="h-30 mx-auto opacity-90" />
    </div>

    <!-- menu title -->
    <h3 class="uppercase text-white/70 text-xs tracking-widest mb-4">
      Menu
    </h3>

    <!-- nav items -->
    <nav class="flex flex-col gap-2">

      <!-- dashboard (logged-in only) -->
      <Link
        v-if="$page.props.auth.user"
        href="/dashboard"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">space_dashboard</span>
        <span>Dashboard</span>
      </Link>

      <!-- home -->
      <Link
        href="/"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">home</span>
        <span>Home</span>
      </Link>

      <!-- guidelines -->
      <Link
        href="/guidelines"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/guidelines') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">menu_book</span>
        <span>Guidelines</span>
      </Link>

      <!-- challenges -->
      <Link
        v-if="$page.props.auth.user"
        href="/challenges"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/challenges') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">flag</span>
        <span>Challenges</span>
      </Link>

      <!-- teams -->
      <Link
        v-if="$page.props.auth.user"
        href="/teams"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/teams') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">groups</span>
        <span>Teams</span>
      </Link>

      <!-- badges -->
      <Link
        v-if="$page.props.auth.user"
        href="/badges"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/badges') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">emoji_events</span>
        <span>Badges</span>
      </Link>

      <!-- scoreboard -->
      <Link
        href="/scoreboard"
        @click="closeSidebar"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg transition',
          isActive('/scoreboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'
        ]"
      >
        <span class="material-symbols-outlined">leaderboard</span>
        <span>Scoreboard</span>
      </Link>

    </nav>

    <div class="flex-1"></div>

    <!-- logout -->
    <Link
      v-if="$page.props.auth.user"
      as="button"
      @click="() => { handleLogout(); closeSidebar(); }"
      class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/80 
            hover:bg-white/10 hover:text-white transition w-full text-left"
    >
      <span class="material-symbols-outlined">logout</span>
      <span>Log out</span>
    </Link>
  </aside>
</template>
