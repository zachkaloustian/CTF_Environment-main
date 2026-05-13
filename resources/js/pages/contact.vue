<template>
  <div class="min-h-screen bg-white text-gray-800">
    <!-- Header -->
    <header class="flex justify-between items-center px-8 py-3 bg-[#0b3d91] border-b-4 border-[#005ea5]">
      <div class="flex items-center">
        <img src="/images/ecpi-logo.png" alt="ECPI Logo" class="h-10" />
        <nav class="ml-4 space-x-4">
          <a href="#" class="text-white hover:underline">Guidelines</a>
          <a href="#" class="text-white hover:underline">Challenges</a>
        </nav>
      </div>

      <div class="space-x-4">
        <a href="#" class="text-white hover:underline">Register</a>
        <a href="#" class="text-white hover:underline">Login</a>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto my-12 px-4">
      <div class="text-center mb-10">
        <img
          src="/images/ctf-logo.png"
          alt="CTFd Logo"
          class="mx-auto mb-5 w-72"
        />
        <h1 class="text-3xl font-bold text-[#0b3d91]">
          Welcome to the Contact Page!
        </h1>
      </div>

      <div class="space-y-5 leading-relaxed text-lg">
        <p>
          Congratulations on your decision to participate in our CTF event! We
          look forward to seeing how you do.
        </p>

        <p>
          A CTF consists of challenges at various levels of difficulty — novice,
          intermediate, and advanced. The more advanced challenges have more
          points than the lower challenges.
        </p>

        <p>
          The challenges for you to solve are in the
          <span class="font-semibold text-[#0b3d91]">Challenges</span> link
          above!
        </p>

        <div class="space-y-2">
          <p>
            <strong>CyberRange:</strong>
            <a
              href="https://cyberrange.ecpi.edu"
              target="_blank"
              class="text-[#005ea5] hover:underline"
              >https://cyberrange.ecpi.edu</a
            >,
            login information provided by your facilitators.
          </p>
          <p>
            <strong>CTF Env:</strong>
            <a
              href="https://ecpi.ctfd.io"
              target="_blank"
              class="text-[#005ea5] hover:underline"
              >https://ecpi.ctfd.io</a
            >
            — You should already have your user ID and password.
          </p>
        </div>

        <p>
          If you cannot register, please let us know. We have to whitelist your
          domain to be able to register.
        </p>

        <p class="font-semibold uppercase text-[#0b3d91]">
          WARNINGS WILL BE SENT OUT EVERY 15 MINUTES.
        </p>

        <p>
          <strong>Help Desk:</strong> Have your facilitator send a note in chat.
        </p>

        <div class="pt-8">
          <h2 class="text-2xl font-bold text-[#0b3d91] mb-2">Good luck!</h2>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-gray-500 py-6 border-t border-gray-200">
      Powered by laravel
    </footer>
  </div>
</template>

<script lang="ts">
export default {
  name: "contact",
};
</script>
