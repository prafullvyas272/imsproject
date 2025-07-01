<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
      <Head title="Your Product — Landing Page" />

      <!-- Fullscreen wrapper -->
      <div class="w-screen h-screen overflow-hidden flex flex-col bg-gradient-to-br from-blue-600 to-purple-600 relative">
        <!-- Top-right Auth Buttons -->
        <div class="w-full flex justify-end p-6 absolute top-0 left-0 z-10">
          <Link
            href="/login"
            class="bg-white text-blue-700 font-semibold px-5 py-2 rounded-full shadow hover:bg-gray-200 transition mx-2"
          >
            Login
          </Link>
          <Link
            href="/register"
            class="bg-blue-700 text-white font-semibold px-5 py-2 rounded-full shadow hover:bg-blue-800 transition mx-2"
          >
            Register
          </Link>
        </div>

        <!-- Centered Card -->
        <div class="flex flex-1 items-center justify-center px-6">
          <div class="bg-white rounded-2xl shadow-2xl p-10 max-w-xl w-full text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Your Product</h1>
            <p class="text-gray-600 mb-6">
              Simplify your workflow with our all-in-one platform designed for speed, security, and scale.
            </p>
            <Link
              href="/dashboard"
              class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full font-semibold shadow hover:bg-blue-700 transition"
            >
              Explore Dashboard
            </Link>
          </div>
        </div>
      </div>
    </div>


  </template>


