<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue'; // MATCH REGISTER PAGE LAYOUT
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';

// Props from backend
defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

// Tell Vue/Inertia to use AppLayout
defineOptions({
  layout: AppLayout,
});
</script>

<template>
  <!-- head -->
  <Head title="Login" />

  <!-- logo + title -->
  <div class="flex flex-col items-center mt-5 mb-6">
    <img
      src="/images/CTFcrop.png"
      class="h-80 mx-auto mb-2 drop-shadow-md"
      alt="CTF Logo"
    />

    <h1 class="text-3xl font-bold text-[#0b3d91]">
      Log In to Your Account
    </h1>

    <p class="text-gray-400 mt-2 text-sm">
      Enter your email and password below.
    </p>
  </div>

  <!-- Login status -->
  <div
    v-if="status"
    class="mb-4 text-center text-sm font-medium text-green-500"
  >
    {{ status }}
  </div>

  <!-- main login panel -->
  <Form
    v-bind="store.form()"
    :reset-on-success="['password']"
    v-slot="{ errors, processing }"
    class="w-full max-w-md mx-auto bg-gray-900 p-8 rounded-xl shadow-lg border border-gray-700 flex flex-col gap-6"
  >
    <div class="grid gap-6">
      <!-- email -->
      <div class="grid gap-2">
        <Label for="email" class="text-gray-300">Email address</Label>
        <Input
          id="email"
          type="email"
          name="email"
          required
          autofocus
          :tabindex="1"
          autocomplete="email"
          placeholder="email@example.com"
          class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
        />
        <InputError :message="errors.email" />
      </div>

      <!-- password -->
      <div class="grid gap-2">
        <Label for="password" class="text-gray-300">Password</Label>
        <Input
          id="password"
          type="password"
          name="password"
          required
          :tabindex="2"
          autocomplete="current-password"
          placeholder="Password"
          class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
        />
        <InputError :message="errors.password" />
      </div>

      <!-- remember me -->
      <div class="flex items-center">
        <Label for="remember" class="flex items-center space-x-3 text-gray-300">
          <Checkbox id="remember" name="remember" :tabindex="3" />
          <span>Remember me</span>
        </Label>
      </div>

      <!-- login button -->
      <Button
        type="submit"
        class="mt-2 w-full bg-[#0b3d91] hover:bg-[#0a2f6d] text-white py-3 rounded-lg font-semibold shadow-md transition"
        :tabindex="4"
        :disabled="processing"
        data-test="login-button"
      >
        <Spinner v-if="processing" />
        Log In
      </Button>
    </div>

    <!-- bottom links (Forgot password + Sign up) -->
    <div class="text-center text-sm text-[#cacaca] mt-4">

      <!-- Forgot Password -->
      <div v-if="canResetPassword" class="mb-2">
        <TextLink
          :href="request()"
          class="underline underline-offset-4 !text-white hover:!text-[#2b569d]"
          :tabindex="5"
        >
          Forgot password?
        </TextLink>
      </div>

      <!-- Sign Up -->
      <div v-if="canRegister">
        Don’t have an account?
        <TextLink
          :href="register()"
          class="underline underline-offset-4 !text-white hover:!text-[#2b569d]"
          :tabindex="6"
        >
          Sign up
        </TextLink>
      </div>

    </div>
  </Form>
</template>
