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

        <div v-for="reply in props.replies" :key="reply.id" class="media-block" >
            <a class="media-left" href="#"><img class="img-circle img-sm" alt="Profile Picture" src="https://bootdey.com/img/Content/avatar/avatar2.png"></a>
            <div class="media-body">
                <div class="mar-btm">
                    <a href="#" class="btn-link text-semibold media-heading box-inline">{{ reply.email }}</a>
                    <p class="text-muted text-sm"><i class="fa fa-mobile fa-lg"></i>{{ reply.created_at }}</p>
                </div>
                <p>{{ reply.text }}</p>
                <div class="pad-ver">
                    <div class="btn-group">
                    </div>
                    <div v-if="reply.replying">
                        <input v-model="reply.reply_name" placeholder="Your Name" />
                        <input v-model="reply.reply_email" placeholder="Your Email" />
                        <textarea v-model="reply.reply_text" placeholder="Your Reply"></textarea>
                        <button @click="addReply(reply)">Add Reply</button>
                    </div>
                    <button class="btn btn-sm btn-default btn-hover-primary" @click="addReply(reply)">Comment</button>
                </div>
                <hr>
                <child-comment-component :replies="reply.descendants"></child-comment-component>
            </div>
        </div>
</template>
