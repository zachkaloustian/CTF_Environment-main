<script setup lang="ts">
import Heading from '@/components/Heading.vue'
import { Separator } from '@/components/ui/separator'
import { toUrl, urlIsActive } from '@/lib/utils'
import { edit as editAppearance } from '@/routes/appearance'
import { edit as editProfile } from '@/routes/profile'
import { show } from '@/routes/two-factor'
import { edit as editPassword } from '@/routes/user-password'
import { Link } from '@inertiajs/vue3'

// Defining icons
const sidebarNavItems = [
  {
    title: 'Profile',
    href: editProfile(),
    icon: 'account_circle',
  },
  {
    title: 'Password',
    href: editPassword(),
    icon: 'key',
  },
  {
    title: 'Two-Factor Auth',
    href: show(),
    icon: 'shield_lock',
  },
  {
    title: 'Appearance',
    href: editAppearance(),
    icon: 'palette',
  },
]

const currentPath = typeof window !== undefined ? window.location.pathname : ''
</script>

<template>
  <div class="px-4 py-6 text-gray-200">
    <!-- Page title -->
    <Heading
      title="Settings"
      description="Manage your profile and account settings"
      class="text-white"
    />

    <div class="flex flex-col lg:flex-row lg:space-x-12 mt-6">

      <!-- Left sidebar navigation -->
      <aside class="w-full max-w-xl lg:w-48">
        <nav class="flex flex-col space-y-2">
          <Link
            v-for="item in sidebarNavItems"
            :key="toUrl(item.href)"
            :href="item.href"
            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                   hover:bg-gray-800 hover:text-white
                   text-gray-300"
            :class="{
              'bg-gray-900 border border-blue-500 text-white shadow-lg':
                urlIsActive(item.href, currentPath),
            }"
          >
            <span class="material-symbols-outlined">
              {{ item.icon }}
            </span>
            <span>{{ item.title }}</span>
          </Link>
        </nav>
      </aside>

      <!-- Separator for mobile view -->
      <Separator class="my-6 lg:hidden" />

      <!-- Right content area -->
      <div class="flex-1 md:max-w-2xl">
        <section class="max-w-xl space-y-12">
          <slot />
        </section>
      </div>
    </div>
  </div>
</template>
