<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft, Users } from 'lucide-vue-next';

defineOptions({
  layout: AppLayout,
});

const form = useForm({
  name: '',
  description: '',
  max_size: 5,
  is_public: true,
});

const submit = () => {
  form.post('/teams', {
    onSuccess: () => {
      // Redirect handled by controller
    },
  });
};
</script>

<template>
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <Button
        variant="ghost"
        @click="$inertia.visit('/teams')"
        class="mb-4"
      >
        <ArrowLeft class="w-4 h-4 mr-2" />
        Back to Teams
      </Button>

      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Your Team</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Form a team to compete together in challenges
      </p>
    </div>

    <!-- Create Form -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center">
          <Users class="w-5 h-5 mr-2" />
          Team Details
        </CardTitle>
        <CardDescription>
          Set up your team's basic information and preferences
        </CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Team Name -->
          <div class="space-y-2">
            <Label for="name">Team Name *</Label>
            <Input
              id="name"
              v-model="form.name"
              placeholder="Enter your team name"
              required
              :class="{ 'border-red-500': form.errors.name }"
            />
            <p v-if="form.errors.name" class="text-sm text-red-600">
              {{ form.errors.name }}
            </p>
          </div>

          <!-- Description -->
          <div class="space-y-2">
            <Label for="description">Description</Label>
            <textarea
              id="description"
              v-model="form.description"
              placeholder="Brief description of your team (optional)"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
              :class="{ 'border-red-500': form.errors.description }"
            />
            <p v-if="form.errors.description" class="text-sm text-red-600">
              {{ form.errors.description }}
            </p>
          </div>

          <!-- Max Size -->
          <div class="space-y-2">
            <Label for="max_size">Maximum Team Size *</Label>
            <Input
              id="max_size"
              v-model.number="form.max_size"
              type="number"
              min="1"
              max="10"
              required
              :class="{ 'border-red-500': form.errors.max_size }"
            />
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Choose between 1-10 members. You can change this later.
            </p>
            <p v-if="form.errors.max_size" class="text-sm text-red-600">
              {{ form.errors.max_size }}
            </p>
          </div>

          <!-- Public/Private -->
          <div class="flex items-center space-x-2">
            <Checkbox
              id="is_public"
              v-model:checked="form.is_public"
            />
            <Label for="is_public" class="text-sm">
              Make team public (visible to others for joining)
            </Label>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-4 pt-4">
            <Button
              type="button"
              variant="outline"
              @click="$inertia.visit('/teams')"
            >
              Cancel
            </Button>
            <Button
              type="submit"
              :disabled="form.processing"
              class="bg-blue-600 hover:bg-blue-700"
            >
              <Users class="w-4 h-4 mr-2" />
              Create Team
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>

    <!-- Info Box -->
    <Card class="mt-6 bg-blue-50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-800">
      <CardContent class="pt-6">
        <div class="flex items-start">
          <Users class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" />
          <div>
            <h3 class="font-medium text-blue-900 dark:text-blue-100">What happens next?</h3>
            <ul class="mt-2 text-sm text-blue-800 dark:text-blue-200 space-y-1">
              <li>• You'll automatically become the team captain</li>
              <li>• You'll receive a join code to share with potential members</li>
              <li>• You can invite members via email or they can join using the code</li>
              <li>• You can manage team settings and membership from the team page</li>
            </ul>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>