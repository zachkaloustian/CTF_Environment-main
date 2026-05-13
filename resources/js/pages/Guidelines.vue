<template>
  <AppLayout>
    <div class="text-center mb-12">
      <img
        src="/images/ctflogo.png"
        class="mx-auto w-64 mb-6"
        alt="CTF Logo"
      />

      <h1 class="text-3xl font-bold text-white mb-4 tracking-wide">
        CTF GUIDELINES
      </h1>

      <p class="text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto">
        Welcome to the ECPI Capture the Flag event! Please read the guidelines
        below to understand how the tournament works and what rules must be followed.
      </p>
    </div>

    <!-- Guidelines Section -->
    <section class="max-w-4xl mx-auto bg-gray-900/60 p-8 rounded-xl shadow-lg border border-gray-700 mb-12">
      <h2 class="text-2xl font-bold text-blue-300 mb-4">The Do and Do Nots</h2>

      <ol class="list-decimal list-inside space-y-4 text-gray-300 leading-relaxed">
        <li>
          All CTF players are expected to act professionally. Refrain from using
          inappropriate team names or screen names. Treat CTF staff, coaches, and
          fellow participants with respect.
        </li>

        <li>
          <strong class="text-red-400">DO NOT</strong> attack the CTF website,
          scoreboard, or other players. Do not intercept, duplicate, or alter another
          player's attempts. Do not alter or deface challenges.
        </li>

        <li>
          <strong class="text-green-400">DO</strong> attack only the targets
          designated in the challenges and stay within the approved virtual range.
        </li>

        <li>
          Exercise care when handling tournament artifacts. These may include
          Wireshark samples, binaries, or other digital materials.
        </li>

        <li>
          Players are forbidden from sharing TTPs (Techniques, Tactics, Procedures)
          during the event. Team collaboration is allowed within your team only.
        </li>

        <li>
          Do not post tournament material publicly without explicit permission.
        </li>

        <li>
          Keep notes and ask questions after the tournament. The event is meant to
          spread knowledge and be fun!
        </li>
      </ol>
    </section>

    <!-- Scoring Section -->
    <section class="max-w-4xl mx-auto bg-gray-900/60 p-8 rounded-xl shadow-lg border border-gray-700">
      <h2 class="text-2xl font-bold text-blue-300 mb-4">Scoring System Outline</h2>

      <ol class="list-decimal list-inside space-y-4 text-gray-300 leading-relaxed">
        <li>
          <strong>Flag Structure:</strong> Most flags follow the format
          <span class="text-blue-400 font-mono">ECPI-ABCD-1234</span>
          (4 random letters + 4 random numbers). Some challenges (password cracking,
          wireless, syntax-based puzzles) may differ.
        </li>

        <li>
          <strong>Tier 1 — Easy:</strong> Designed for beginners. Usually solved in
          one or very few steps. Low point value.
        </li>

        <li>
          <strong>Tier 2 — Medium:</strong> Builds on Tier 1 knowledge. Includes
          multiple steps and light obfuscation. Worth more points.
        </li>

        <li>
          <strong>Tier 3 — Hard:</strong> Multi-step, challenging problems often
          requiring multiple cybersecurity disciplines. “Boss Challenges” fall here
          and are worth the most points.
        </li>
      </ol>
    </section>
  </AppLayout>
</template>

<script lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'

export default {
  name: 'Guidelines',
  layout: AppLayout,
}
</script>
