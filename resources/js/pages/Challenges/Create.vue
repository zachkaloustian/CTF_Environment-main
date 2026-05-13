<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

// Load categories from page props
const page = usePage();
const categories = page.props.categories as Array<{ id: number; name: string }>;

// Flash message
const flash = page.props.flash as { success?: string } | undefined;

// Form fields
const title = ref("");
const description = ref("");
const difficulty = ref("easy");
const points = ref(100);
const category_id = ref("");
const flag = ref("");
const tags = ref("");
const est_time = ref("");

// Submit handler
const submitForm = () => {
    router.post("/challenges", {
        title: title.value,
        description: description.value,
        difficulty: difficulty.value,
        points: points.value,
        category_id: category_id.value,
        flag: flag.value,
        tags: tags.value,
        est_time: est_time.value,
    });
};
</script>

<template>
  <AppLayout>
    <div class="text-gray-200 max-w-3xl mx-auto p-6">
        
      <h1 class="text-3xl font-bold text-[#075ae9] mb-6">Create Challenge</h1>

      <!-- Success message -->
      <div 
        v-if="flash?.success" 
        class="bg-green-600/30 border border-green-500 text-green-300 p-3 rounded mb-6"
      >
        {{ flash.success }}
      </div>

      <div class="bg-gray-900 border border-gray-700 p-6 rounded-xl shadow-lg space-y-6">

        <!-- Title -->
        <div>
          <label class="block text-gray-300 mb-1">Title</label>
          <input
            v-model="title"
            type="text"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
            placeholder="SQL Injection 101"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="block text-gray-300 mb-1">Description</label>
          <textarea
            v-model="description"
            rows="5"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
            placeholder="Challenge description here..."
          ></textarea>
        </div>

        <!-- Difficulty -->
        <div>
          <label class="block text-gray-300 mb-1">Difficulty</label>
          <select
            v-model="difficulty"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
          >
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
        </div>

        <!-- Points -->
        <div>
          <label class="block text-gray-300 mb-1">Points</label>
          <input
            v-model="points"
            type="number"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
          />
        </div>

        <!-- Category -->
        <div>
          <label class="block text-gray-300 mb-1">Category</label>
          <select
            v-model="category_id"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
          >
            <option disabled value="">-- select a category --</option>

            <option 
              v-for="cat in categories" 
              :key="cat.id" 
              :value="cat.id"
            >
              {{ cat.name }}
            </option>
          </select>
        </div>

        <!-- Flag -->
        <div>
          <label class="block text-gray-300 mb-1">Flag</label>
          <input
            v-model="flag"
            type="text"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
            placeholder="ECPI-ABCD-1234"
          />
        </div>

        <!-- Tags -->
        <div>
          <label class="block text-gray-300 mb-1">Tags (optional)</label>
          <input
            v-model="tags"
            type="text"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
            placeholder="sql, injection, beginner"
          />
        </div>

        <!-- Estimated time -->
        <div>
          <label class="block text-gray-300 mb-1">Estimated Time (minutes)</label>
          <input
            v-model="est_time"
            type="number"
            class="w-full p-3 bg-gray-800 border border-gray-700 rounded text-gray-200"
            placeholder="Optional"
          />
        </div>

        <!-- Submit -->
        <button
          @click="submitForm"
          class="w-full bg-[#075ae9] hover:bg-[#0a44b9] text-white py-3 rounded-lg font-semibold shadow-md transition"
        >
          Create Challenge
        </button>

      </div>
    </div>
  </AppLayout>
</template>
