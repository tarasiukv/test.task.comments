<script setup>
import { defineProps } from "vue";
import useComments from "../composables/comment.js";
import useAddingComments from "../composables/addingComment.js";

const props = defineProps({
  replies: Array,
})

const { comments, storeComment } = useComments();
const { replyToComment, addReply} = useAddingComments();

</script>

<template>

    <div v-for="reply in props.replies" :key="reply.id">
        <a class="media-left" href="#">
            <img class="img-circle img-sm" alt="Profile Picture" src="https://bootdey.com/img/Content/avatar/avatar2.png">
        </a>
        <div class="media-body">
            <div class="mar-btm">
                <a href="#" class="btn-link text-semibold media-heading box-inline">{{ reply.user?.email }}</a>
                <p class="text-muted text-sm">{{ reply.created_at }}</p>
            </div>
            <div v-html="reply.text"></div>
            <div class="pad-ver">
                <button class="btn btn-sm btn-default btn-hover-primary" @click="replyToComment(reply)">
                    <i class="fa fa-reply"></i>
                    Add comment
                </button>
                <div v-if="reply.replying">
                    <div class="form">
                        <input v-model="reply.reply_name" placeholder="Your Name"/>
                    </div>
                    <div class="form">
                        <input v-model="reply.reply_email" placeholder="Your Email"/>
                    </div>
                    <div class="form">
                        <textarea v-model="reply.reply_text" placeholder="Your Reply"></textarea>
                    </div>
                    <button @click="addReply(reply)">
                        <i class="fa fa-reply"></i>
                        Add Reply
                    </button>
                </div>
            </div>
            <hr>
            <child-comment-component :replies="reply.descendants"></child-comment-component>
        </div>
    </div>
</template>

<style scoped>
.form {
    margin-bottom: 10px;
}
</style>
