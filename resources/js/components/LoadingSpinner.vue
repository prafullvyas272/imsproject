<template>
    <div v-if="visible" class="loader-container">
        <div class="loader"></div>
        <p v-if="message" class="loader-message">{{ message }}</p>
    </div>
</template>

<script lang="ts">
export default {
    name: "LoadingSpinner",
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
        message: {
            type: String,
            default: "", // Optional loading message
        },
    },
};
</script>

<style scoped>
.loader-container {
    position: fixed;
    /* Ensures the loader covers the entire screen */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.7);
    /* Dark semi-transparent background */
    z-index: 9999;
    /* Ensures it appears on top of other elements */
}

.loader {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    /* Light gray */
    border-top: 5px solid #3498db;
    /* Blue */
    border-radius: 50%;
    animation: spin 1s linear infinite;
    /* Spinner animation */
}

.loader-message {
    margin-top: 0px;
    color: #ffffff;
    font-size: 16px;
    text-align: center;
    margin-left: 20px;
    font-size: xx-large;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
