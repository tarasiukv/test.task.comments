<script setup>
import { defineProps } from "vue";
import useComments from "../composables/comment.js";

const props = defineProps({
  replies: Array,
})

const { comments, storeComment } = useComments();

const replyToComment = (comment) => {
    comment.replying = true;
    comment.reply_name = "";
    comment.reply_email = "";
    comment.reply_text = "";
};

const addReply = (comment) => {

    const data = {
        name: comment.reply_name,
        email: comment.reply_email,
        text: comment.reply_text,
        comment_id: comment.id,
    };
    if (storeComment(data)) {
        comment.replying = false;
        comment.reply_name = "";
        comment.reply_email = "";
        comment.reply_text = "";
    } else {
    }
};

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
        <child-comment-component :replies="reply.descendants"></child-comment-component>
      </li>
    </ul>
</template>
