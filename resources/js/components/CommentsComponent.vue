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



    <div class="container bootdey">
        <div class="col-md-12 bootstrap snippets">
            <div class="panel">
                <div class="panel-body">
                    <h3>Add a New Comment</h3>
                    <form @submit.prevent="addNewComment">
                        <div>
                            <p>Enter <input v-model="newComment.name" class="form-control"  placeholder="Your Name" required/></p>
                        </div>
                        <div>
                            <p>Enter <input v-model="newComment.email" class="form-control"  placeholder="Your Email" required/></p>
                        </div>
                        <textarea
                            v-model="newComment.text"
                            class="form-control"
                            rows="2"
                            placeholder="What are you thinking?"
                            required
                        ></textarea>
                        <div class="mar-top clearfix">
                            <button class="btn btn-sm btn-primary pull-right" type="submit"><i class="fa fa-pencil fa-fw"></i> Share</button>
                            <a class="btn btn-trans btn-icon fa fa-camera add-tooltip" href="#"></a>
                            <a class="btn btn-trans btn-icon fa fa-file add-tooltip" href="#"></a>
                        </div>
                        <div>
                            <button type="submit">Add Comment</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel">
                <div
                    class="panel-body"
                    v-for="comment in comments" :key="comment.id"
                >
                    <div class="media-block">
                        <a class="media-left" href="#"><img class="img-circle img-sm" alt="Profile Picture" src="https://bootdey.com/img/Content/avatar/avatar1.png"></a>
                        <div class="media-body">
                            <div class="mar-btm">
                                <a href="#" class="btn-link text-semibold media-heading box-inline"> {{ comment.user.email }}</a>
                                <p class="text-muted text-sm"> {{ comment.created_at }}</p>
                            </div>
                            <p>{{ comment.text }}</p>
<!--                            For images or files    -->
<!--                            <img class="img-responsive thumbnail" src="" alt="Image">-->
                            <div class="pad-ver">
                                <button class="btn btn-sm btn-default btn-hover-primary" @click="replyToComment(comment)"><i class="fa fa-reply"></i> Comment</button>
                                <button @click="toggleChildComments(comment.id)" class="btn btn-sm btn-default btn-hover-primary">
                                    {{ showComments[comment.id] ? 'Hide' : 'Show' }} comments
                                </button>
                            </div>

                            <div v-if="comment.replying">
                                <div><input v-model="comment.reply_name" placeholder="Your Name" /></div>
                                <input v-model="comment.reply_email" placeholder="Your Email" />
                                <textarea v-model="comment.reply_text" class="form-control" placeholder="Your Reply" required></textarea>
                                <button @click="addReply(comment)">Add Reply</button>
                            </div>

                            <hr>
                            <div v-if="showComments[comment.id]">
                                <child-comment-component :replies="comment.descendants" />
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
