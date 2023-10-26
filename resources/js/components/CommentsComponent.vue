<script setup>
import { ref, onMounted } from "vue";
import useComments from "../composables/comment.js";
import ChildCommentComponent from "./ChildCommentComponent.vue";

const {
  getComments,
  nextPage,
  prevPage,
  comments,
  comment_page,
  comment_page_count,
  storeComment,
} = useComments();

const newComment = ref({
  name: "",
  email: "",
  text: "",
});

onMounted(() => {
  getComments();
});

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
    parent_id: comment.id,
  };

  if (storeComment(data)) {
    // Optionally: Handle successful reply addition
    comment.replying = false;
    comment.reply_name = "";
    comment.reply_email = "";
    comment.reply_text = "";
  } else {
    // Optionally: Handle reply addition error
  }
};

const addNewComment = () => {
  if (newComment.value.name && newComment.value.email && newComment.value.text) {
    if (storeComment(newComment.value)) {
      // Optionally: Handle successful new comment addition
      newComment.value.name = "";
      newComment.value.email = "";
      newComment.value.text = "";
    } else {
      // Optionally: Handle new comment addition error
    }
  }
};

const loadMoreComments = () => {
  nextPage();
};
</script>

<template>
  <div>
    <h2>Comments</h2>

    <!-- New Comment Form -->
    <div>
      <h3>Add a New Comment</h3>
      <form @submit.prevent="addNewComment">
        <div>
          <input v-model="newComment.name" placeholder="Your Name" required />
        </div>
        <div>
          <input v-model="newComment.email" placeholder="Your Email" required />
        </div>
        <div>
          <textarea v-model="newComment.text" placeholder="Your Comment" required></textarea>
        </div>
        <div>
          <button type="submit">Add Comment</button>
        </div>
      </form>
    </div>

    <ul>
      <li v-for="comment in comments" :key="comment.id">
        <div>
          <strong>{{ comment.id }}</strong> - {{ comment.email }}
        </div>
        <div>{{ comment.text }}</div>
        <button @click="replyToComment(comment)">Reply</button>
        <div v-if="comment.replying">
          <input v-model="comment.reply_name" placeholder="Your Name" />
          <input v-model="comment.reply_email" placeholder="Your Email" />
          <textarea v-model="comment.reply_text" placeholder="Your Reply"></textarea>
          <button @click="addReply(comment)">Add Reply</button>
        </div>
        <child-comment-component :replies="comment.childComments"/>
      </li>
    </ul>
    <button @click="loadMoreComments" v-if="comment_page < comment_page_count">
      Load More
    </button>
  </div>
</template>

