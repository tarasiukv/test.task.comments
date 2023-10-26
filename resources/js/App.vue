<script setup>
import {ref, reactive, watch, computed, onMounted, onBeforeMount, defineProps, provide} from "vue";
import {useRoute} from "vue-router";

const route = useRoute();

onMounted(async () => {})
</script>

<template>
    <router-view></router-view>
</template>

