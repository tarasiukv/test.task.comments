<script setup>
import { ref, onMounted, defineProps } from "vue";
import useComments from "../composables/comment.js";


const props = defineProps({
  replies: Array,
})


</script>

<template>

    <ul>
      <li v-for="reply in props.replies" :key="reply.id">
        <div>
          <strong>{{ reply.id }}</strong> - {{ reply.email }}
        </div>
        <div>{{ reply.text }}</div>
        <button @click="replyToComment(reply)">Reply</button>
        <div v-if="reply.replying">
          <input v-model="reply.reply_name" placeholder="Your Name" />
          <input v-model="reply.reply_email" placeholder="Your Email" />
          <textarea v-model="reply.reply_text" placeholder="Your Reply"></textarea>
          <button @click="addReply(reply)">Add Reply</button>
        </div>
        <comment-replies :replies="reply.replies"></comment-replies>
      </li>
    </ul>
</template>
