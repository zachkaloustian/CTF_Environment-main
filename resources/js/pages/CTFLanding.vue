<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
</script>

<template>
  <AppLayout>
    <!-- Top Hero Section -->
    <div class="text-center mb-12">
      <img
        src="/images/ctflogo.png"
        class="mx-auto w-64 mb-6"
        alt="CTF Logo"
      />

      <h1 class="text-3xl font-bold text-white mb-4 tracking-wide">
        Welcome to ECPI's CTF Competition!
      </h1>

      <p class="text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto">
        Congratulations on your decision to participate in our CTF event!
        We look forward to seeing how you perform.
      </p>
    </div>

    <!-- Main Info Section -->
    <section class="max-w-4xl mx-auto bg-gray-900/60 p-8 rounded-xl shadow-lg border border-gray-700 mb-12">
      <h2 class="text-2xl font-bold text-blue-300 mb-4">About This Competition</h2>

      <p class="text-gray-300 leading-relaxed mb-4">
        A Capture the Flag (CTF) consists of challenges at various levels of difficulty. Our event
        includes three tiers:
      </p>

      <ul class="list-disc list-inside text-gray-300 space-y-2 mb-6">
        <li><strong class="text-blue-300">Novice</strong> – Beginner-friendly questions.</li>
        <li><strong class="text-blue-300">Intermediate</strong> – Requires critical thinking.</li>
        <li><strong class="text-blue-300">Advanced</strong> – Complex multi-step challenges.</li>
      </ul>

      <p class="text-gray-300 leading-relaxed">
        Higher difficulty challenges award more points. You can find all available challenges by 
        navigating to the <strong class="text-blue-300">Challenges</strong> page in the sidebar.
      </p>
    </section>

    <!-- Access Info Section -->
    <section class="max-w-4xl mx-auto bg-gray-900/60 p-8 rounded-xl shadow-lg border border-gray-700 mb-12">
      <h2 class="text-2xl font-bold text-blue-300 mb-4">Where You'll Be Working</h2>

      <div class="space-y-6 text-gray-300 leading-relaxed">
        <p>
          <strong class="text-blue-300">CyberRange:</strong>
          <a
            href="https://cyberrange.ecpi.edu"
            target="_blank"
            class="text-blue-400 underline hover:text-blue-200"
          >
            https://cyberrange.ecpi.edu
          </a>
          <br />
          Login information is provided by your facilitators.
        </p>

        <p>
          <strong class="text-blue-300">CTF Environment:</strong>
          <a
            href="https://ecpi.ctfd.io"
            target="_blank"
            class="text-blue-400 underline hover:text-blue-200"
          >
            https://ecpi.ctfd.io
          </a>
          <br />
          You should already have your user ID and password.
        </p>

        <p>
          If you cannot register, please contact your facilitator. Your domain must be
          whitelisted before registration is allowed.
        </p>
      </div>
    </section>

    <!-- Important Notes Section -->
    <section class="max-w-4xl mx-auto bg-gray-900/60 p-8 rounded-xl shadow-lg border border-gray-700 mb-12">
      <h2 class="text-2xl font-bold text-blue-300 mb-4">Important Notes</h2>

      <ul class="list-disc list-inside space-y-3 text-gray-300 leading-relaxed">
        <li><strong class="text-red-400">WARNINGS WILL BE SENT OUT EVERY 15 MINUTES.</strong></li>
        <li>Need help? Have your facilitator send a message in the event chat.</li>
      </ul>
    </section>

    <!-- Closing Section -->
    <section class="max-w-4xl mx-auto text-center text-gray-300 mb-10">
      <p class="text-lg mb-2">Good luck!</p>
      <p class="text-gray-400 italic">Cheers,</p>
      <p class="text-gray-400">Dean of CIS</p>
    </section>
  </AppLayout>
</template>

<script lang="ts">

export default {
  name: 'CtfLanding',
  layout: AppLayout,
}
</script>
