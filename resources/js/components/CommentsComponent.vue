<script setup>
import {ref, onMounted} from "vue";
import useComments from "../composables/comment.js";
import ChildCommentComponent from "./ChildCommentComponent.vue";

const { getComments, comments, storeComment } = useComments();

const newComment = ref({
    name: "",
    email: "",
    text: "",
});
const showComments = ref({});

const toggleChildComments = (commentId) => {
    showComments.value[commentId] = !showComments.value[commentId];
};

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

const addNewComment = () => {
    if (newComment.value.name && newComment.value.email && newComment.value.text) {
        if (storeComment(newComment.value)) {
            newComment.value.name = "";
            newComment.value.email = "";
            newComment.value.text = "";
        } else {
        }
    }
};

onMounted(async () => {
    await getComments();
});

</script>

<template>
    <div id="wrapper">
        <h2>Comments</h2>

        <div>
            <h3>Add a New Comment</h3>
            <form @submit.prevent="addNewComment">
                <div>
                    <p>Enter <input v-model="newComment.name" placeholder="Your Name" required/></p>
                </div>
                <div>
                    <p>Enter <input v-model="newComment.email" placeholder="Your Email" required/></p>
                </div>
                <div>
                    <h5>Enter your comment</h5>
                    <textarea v-model="newComment.text" placeholder="Your Comment" required></textarea>
                </div>
                <div>
                    <button type="submit">Add Comment</button>
                </div>
            </form>
        </div>
    </div>


    <div class="container mb-5 mt-5">

        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center mb-5">
                        Comments
                    </h3>
                    <div class="row">
                        <div
                            class="col-md-12"
                            v-for="comment in comments" :key="comment.id"
                        >
                            <div class="media">
                                <img src="/public/image/icon.png" alt=""/>
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-8 d-flex">
                                            <h5>{{ comment.user.email }}</h5>
                                            <span>- 2 hours ago</span>
                                        </div>
                                        <div class="col-4">
                                            <div class="pull-right reply">
                                                <button @click="replyToComment(comment)"><span><i class="fa fa-reply"></i> reply</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ comment.text }}
                                    <div v-if="comment.replying">
                                        <input v-model="comment.reply_name" placeholder="Your Name" />
                                        <input v-model="comment.reply_email" placeholder="Your Email" />
                                        <textarea v-model="comment.reply_text" placeholder="Your Reply"></textarea>
                                        <button @click="addReply(comment)">Add Reply</button>
                                    </div>
                                    <button @click="toggleChildComments(comment.id)">
                                        {{ showComments[comment.id] ? 'Hide' : 'Show' }} comments
                                    </button>
                                    <div v-if="showComments[comment.id]">
                                        <child-comment-component :replies="comment.descendants" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
