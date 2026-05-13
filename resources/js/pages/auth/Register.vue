<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue'; //use AppLayout instead
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';

// Tell Inertia/Vue to use AppLayout as this page's layout
defineOptions({
  layout: AppLayout,
});
</script>

<template>
    <!--Header-->
    <Head title="Register" />
    <!--Logo and spacing-->
    <div class="flex flex-col items-center mt-5 mb-4">
      <img
        src="/images/CTFcrop.png"
        class="h-80 mx-auto mb-2 drop-shadow-md"
        alt="CTF Logo"
      />

      <h1 class="text-3xl font-bold text-[#0b3d91]">
        Create Your Account
      </h1>

      <p class="text-gray-400 mt-2 text-sm">
        Join the CTF and start competing.
      </p>
    </div>

    <Form
      v-bind="store.form()"
      :reset-on-success="['password', 'password_confirmation']"
      v-slot="{ errors, processing }"
      class="w-full max-w-md mx-auto bg-gray-900 p-8 rounded-xl shadow-lg border border-gray-700 flex flex-col gap-6"
    >
      <div class="grid gap-6">

        <!-- Name -->
        <div class="grid gap-2">
          <Label for="name" class="text-gray-300">Name</Label>
          <Input
            id="name"
            type="text"
            required
            autofocus
            :tabindex="1"
            autocomplete="name"
            name="name"
            placeholder="Full name"
            class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
          />
          <InputError :message="errors.name" />
        </div>

        <!-- Email -->
        <div class="grid gap-2">
          <Label for="email" class="text-gray-300">Email address</Label>
          <Input
            id="email"
            type="email"
            required
            :tabindex="2"
            autocomplete="email"
            name="email"
            placeholder="email@example.com"
            class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
          />
          <InputError :message="errors.email" />
        </div>

        <!-- Password -->
        <div class="grid gap-2">
          <Label for="password" class="text-gray-300">Password</Label>
          <Input
            id="password"
            type="password"
            required
            :tabindex="3"
            autocomplete="new-password"
            name="password"
            placeholder="Password"
            class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
          />
          <InputError :message="errors.password" />
        </div>

        <!-- Confirm Password -->
        <div class="grid gap-2">
          <Label for="password_confirmation" class="text-gray-300">
            Confirm password
          </Label>
          <Input
            id="password_confirmation"
            type="password"
            required
            :tabindex="4"
            autocomplete="new-password"
            name="password_confirmation"
            placeholder="Confirm password"
            class="bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500"
          />
          <InputError :message="errors.password_confirmation" />
        </div>

        <!-- Submit Button -->
        <Button
          type="submit"
          class="mt-2 w-full bg-[#0b3d91] hover:bg-[#0a2f6d] text-white py-3 rounded-lg font-semibold shadow-md transition"
          tabindex="5"
          :disabled="processing"
        >
          <Spinner v-if="processing" />
          Create account
        </Button>
      </div>

      <div class="text-center text-sm text-[#cacaca] mt-4">
        Already have an account?
        <TextLink
          :href="login()"
          class="underline underline-offset-4 !text-[#ffffff] hover:!text-[#2b569d]"
          :tabindex="6"
        >
          Log in
        </TextLink>
      </div>
    </Form>
</template>