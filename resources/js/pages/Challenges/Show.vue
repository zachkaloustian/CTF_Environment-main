<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    challenge: {
        id: string;
        title: string;
        description: string;
        difficulty: "easy" | "medium" | "hard";
        points: number;
        category: string;
    };
    versions: Array<{
        id: number;
        version: number;
        difficulty: string;
        points: number;
        updated_at: string; // full timestamp from DB
    }>;
    correct?: boolean | null;
    showBadgeNotification?: boolean;
}>();

console.log('Show Badge Notification Prop:', props.showBadgeNotification);
console.log('All Props:', props);

// Toggle for collapse panel
const showVersions = ref(false);

// Badge earned notification
const showBadgeNotification = ref(!!props.showBadgeNotification);

// Difficulty color classes
const difficultyColor: Record<"easy" | "medium" | "hard", string> = {
    easy: "text-green-400 border-green-400",
    medium: "text-yellow-400 border-yellow-400",
    hard: "text-red-400 border-red-400",
};

const flag = ref("");

// Send flag to backend
const submitFlag = () => {
    router.post(`/challenges/${props.challenge.id}/submit`, {
        flag: flag.value,
    });
};
</script>

<template>
  <AppLayout>
    <div class="text-gray-200 p-6 max-w-4xl mx-auto">

      <!-- BADGE EARNED TOAST -->
      <div v-if="showBadgeNotification" class="fixed top-0 left-0 w-full bg-blue-600 text-white px-6 py-3 shadow-lg z-[1000] flex items-center justify-center">
        <i class="fas fa-trophy text-xl mr-3 text-yellow-300"></i>
        <div>
          <div class="font-semibold">Badge Earned!</div>
          <div class="text-sm">Congratulations! Check your badges page.</div>
        </div>
        <button @click="showBadgeNotification = false" class="ml-4 text-white hover:text-gray-200 text-lg">
          ×
        </button>
      </div>

      <!-- TITLE -->
      <h1 class="text-3xl font-bold text-[#075ae9] mb-2">
        {{ challenge.title }}
      </h1>

      <p class="text-gray-400 mb-4 capitalize">{{ challenge.category }}</p>

      <!-- DIFFICULTY + POINTS -->
      <div class="flex items-center gap-4 mb-6">
        <span
          class="px-3 py-1 text-sm rounded border capitalize"
          :class="difficultyColor[challenge.difficulty]"
        >
          {{ challenge.difficulty }}
        </span>

        <span class="text-blue-300 font-semibold text-lg">
          {{ challenge.points }} pts
        </span>
      </div>

      <!-- COLLAPSIBLE VERSION HISTORY BUTTON -->
      <button
        @click="showVersions = !showVersions"
        class="px-4 py-2 mb-4 rounded-lg bg-gray-800 border border-gray-700
          hover:bg-gray-700 transition text-sm font-medium"
      >
        {{ showVersions ? "Hide Version History" : "View Version History" }}
      </button>

      <!-- VERSION HISTORY PANEL -->
      <div
        v-if="showVersions"
        class="bg-gray-900 border border-gray-700 p-4 rounded-xl mb-6 shadow-lg"
      >
        <h2 class="text-lg font-semibold mb-3 text-gray-200">Version History</h2>

        <div v-if="versions.length === 0" class="text-gray-400 text-sm">
          No previous versions.
        </div>

        <div
          v-for="v in versions"
          :key="v.id"
          class="p-3 mb-3 rounded bg-gray-800 border border-gray-700"
        >
          <p class="text-sm text-gray-200 font-bold">
            Version {{ v.version }}
          </p>

          <p class="text-xs text-gray-400 capitalize">
            Difficulty: {{ v.difficulty }}
          </p>

          <p class="text-xs text-gray-400">{{ v.points }} pts</p>

          <p class="text-xs text-gray-500 mt-1">
            {{ new Date(v.updated_at).toLocaleString("en-US", {
              dateStyle: "medium",
              timeStyle: "short"
            }) }}
          </p>
        </div>
      </div>

      <!-- DESCRIPTION -->
      <p class="mb-8 text-gray-300 whitespace-pre-line">
        {{ challenge.description }}
      </p>

      <!-- FLASH MESSAGES -->
      <div
        v-if="correct === true"
        class="bg-green-600/30 text-green-300 p-3 rounded mb-4 border border-green-500"
      >
        Correct flag!
      </div>

      <div
        v-if="correct === false"
        class="bg-red-600/30 text-red-300 p-3 rounded mb-4 border border-red-500"
      >
        Incorrect flag.
      </div>

      <!-- BADGE EARNED NOTIFICATION -->
      <div
        v-if="showBadgeNotification"
        class="bg-blue-600/30 text-blue-300 p-4 rounded mb-4 border border-blue-500 relative"
      >
        <button
          @click="showBadgeNotification = false"
          class="absolute top-2 right-2 text-blue-300 hover:text-blue-100"
        >
          ×
        </button>
        <div class="flex items-center">
          <i class="fas fa-trophy text-2xl mr-3"></i>
          <div>
            <h3 class="font-semibold">Badge Earned!</h3>
            <p class="text-sm">Congratulations! You've earned a new badge. Check your badges page to see it.</p>
          </div>
        </div>
      </div>

      <!-- FLAG FORM -->
      <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg">

        <h2 class="text-xl font-semibold mb-3 text-gray-200">Submit Flag</h2>

        <input
          v-model="flag"
          name="flag"
          type="text"
          placeholder="flag()"
          class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-gray-200 mb-4"
        />

        <button
          @click="submitFlag"
          class="w-full bg-[#075ae9] hover:bg-[#0a44b9] text-white
                 py-2 rounded-lg shadow-md transition"
        >
          Submit
        </button>

      </div>

    </div>
  </AppLayout>
</template>
