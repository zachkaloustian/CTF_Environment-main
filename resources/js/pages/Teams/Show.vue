<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { ArrowLeft, Users, UserPlus, Settings, LogOut, Copy, Check } from 'lucide-vue-next';

defineOptions({
  layout: AppLayout,
});

const props = defineProps<{
  team: {
    id: number;
    name: string;
    description: string;
    captain: {
      id: number;
      name: string;
      email: string;
    };
    members: Array<{
      id: number;
      name: string;
      email: string;
      pivot: {
        joined_at: string;
      };
    }>;
    max_size: number;
    join_code: string;
    is_public: boolean;
  };
  isMember: boolean;
  isCaptain: boolean;
  canJoin: boolean;
  currentUserTeam: any;
}>();

const joinCodeCopied = ref(false);
const joinCodeDialogOpen = ref(false);
const joinCodeForm = useForm({
  join_code: '',
});

const copyJoinCode = async () => {
  try {
    await navigator.clipboard.writeText(props.team.join_code);
    joinCodeCopied.value = true;
    setTimeout(() => joinCodeCopied.value = false, 2000);
  } catch (err) {
    console.error('Failed to copy join code:', err);
  }
};

const joinTeam = () => {
  joinCodeForm.post('/teams/join', {
    onSuccess: () => {
      joinCodeDialogOpen.value = false;
      window.location.reload();
    },
  });
};

const leaveTeam = () => {
  if (confirm('Are you sure you want to leave this team?')) {
    router.post(`/teams/${props.team.id}/leave`, {}, {
      onSuccess: () => window.location.reload(),
    });
  }
};
</script>

<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ team.name }}</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Captain: {{ team.captain.name }}
          </p>
          <div class="flex items-center gap-4 mt-2">
            <Badge variant="secondary">
              {{ team.members.length }}/{{ team.max_size }} members
            </Badge>
            <Badge v-if="!team.is_public" variant="outline">
              Private Team
            </Badge>
          </div>
        </div>

        <div class="flex gap-2">
          <!-- Captain Actions -->
          <Button
            v-if="isCaptain"
            @click="$inertia.visit(`/teams/${team.id}/manage`)"
            variant="outline"
          >
            <Settings class="w-4 h-4 mr-2" />
            Manage Team
          </Button>

          <!-- Join Team -->
          <Dialog :open="joinCodeDialogOpen" @update:open="joinCodeDialogOpen = $event">
            <DialogTrigger as-child>
              <Button
                v-if="!isMember && canJoin"
                class="bg-blue-600 hover:bg-blue-700"
              >
                <UserPlus class="w-4 h-4 mr-2" />
                Join Team
              </Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>Join {{ team.name }}</DialogTitle>
                <DialogDescription>
                  Enter the join code to become a member of this team.
                </DialogDescription>
              </DialogHeader>
              <form @submit.prevent="joinTeam" class="space-y-4">
                <div>
                  <Label for="join_code">Join Code</Label>
                  <Input
                    id="join_code"
                    v-model="joinCodeForm.join_code"
                    placeholder="Enter join code"
                    required
                    :class="{ 'border-red-500': joinCodeForm.errors.join_code }"
                  />
                  <p v-if="joinCodeForm.errors.join_code" class="text-sm text-red-600 mt-1">
                    {{ joinCodeForm.errors.join_code }}
                  </p>
                </div>
                <div class="flex justify-end gap-2">
                  <Button type="button" variant="outline" @click="joinCodeDialogOpen = false">
                    Cancel
                  </Button>
                  <Button type="submit" :disabled="joinCodeForm.processing">
                    Join Team
                  </Button>
                </div>
              </form>
            </DialogContent>
          </Dialog>

          <!-- Leave Team -->
          <Button
            v-if="isMember && !isCaptain"
            @click="leaveTeam"
            variant="outline"
            class="text-red-600 hover:text-red-700 hover:border-red-300"
          >
            <LogOut class="w-4 h-4 mr-2" />
            Leave Team
          </Button>
        </div>
      </div>
    </div>

    <!-- Team Description -->
    <Card v-if="team.description" class="mb-6">
      <CardContent class="pt-6">
        <p class="text-gray-700 dark:text-gray-300">{{ team.description }}</p>
      </CardContent>
    </Card>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Members List -->
      <div class="lg:col-span-2">
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center">
              <Users class="w-5 h-5 mr-2" />
              Team Members
            </CardTitle>
            <CardDescription>
              {{ team.members.length }} of {{ team.max_size }} spots filled
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div
                v-for="member in team.members"
                :key="member.id"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <div class="flex items-center space-x-3">
                  <div>
                    <p class="font-medium text-gray-900 dark:text-white">
                      {{ member.name }}
                      <Badge v-if="member.id === team.captain.id" variant="default" class="ml-2 text-xs">
                        Captain
                      </Badge>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      Joined {{ new Date(member.pivot.joined_at).toLocaleDateString() }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Empty slots -->
              <div
                v-for="n in team.max_size - team.members.length"
                :key="`empty-${n}`"
                class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 rounded-lg opacity-50"
              >
                <div class="flex items-center space-x-3">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400">Open slot</p>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Team Info Sidebar -->
      <div>
        <Card class="mb-6">
          <CardHeader>
            <CardTitle>Team Information</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div>
              <Label class="text-sm font-medium">Join Code</Label>
              <div class="flex items-center gap-2 mt-1">
                <code class="flex-1 px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-sm font-mono">
                  {{ team.join_code }}
                </code>
                <Button
                  size="sm"
                  variant="outline"
                  @click="copyJoinCode"
                >
                  <Copy v-if="!joinCodeCopied" class="w-4 h-4" />
                  <Check v-else class="w-4 h-4 text-green-600" />
                </Button>
              </div>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                Share this code with people you want to invite
              </p>
            </div>

            <div>
              <Label class="text-sm font-medium">Visibility</Label>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ team.is_public ? 'Public - visible to all users' : 'Private - invite only' }}
              </p>
            </div>

            <div>
              <Label class="text-sm font-medium">Maximum Size</Label>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ team.max_size }} members
              </p>
            </div>
          </CardContent>
        </Card>

        <!-- Current User Status -->
        <Card v-if="currentUserTeam">
          <CardHeader>
            <CardTitle class="text-sm">Your Status</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-sm">
              You are currently a member of
              <span class="font-medium">{{ currentUserTeam.name }}</span>
            </p>
            <p v-if="!isMember" class="text-xs text-gray-600 dark:text-gray-400 mt-1">
              You cannot join multiple teams.
            </p>
          </CardContent>
        </Card>
      </div>
    </div>
  </div>
</template>