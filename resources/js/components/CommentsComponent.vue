<script setup>
import {ref, onMounted, watch} from "vue";
import useComments from "../composables/comment.js";
import ChildCommentComponent from "./ChildCommentComponent.vue";

const { comments, comment_page, comment_page_count, sort_option, getComments, storeComment, nextPage, prevPage, changePage } = useComments();

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

const config = ref({
  placeholderText: 'Edit Your Content Here!',
  charCounterCount: false
})


const initEchoServerListener = async () => {
  window.Echo.channel('comments_adding_channel')
      .listen('NewCommentAdded', async (e) => {
        await getComments()
      })
      .error((err) => {
        console.log(err)
      });
}

onMounted(async () => {
    await getComments();
    await initEchoServerListener()
});
</script>

<template>
    <div class="container bootdey">
        <div class="col-md-12 bootstrap snippets">
            <div class="panel">
                <div class="panel-body">
                    <h3>Add a New Comment</h3>
                    <form @submit.prevent="addNewComment">
                        <div>
                            <p>Enter your name
                                <input
                                    v-model="newComment.name"
                                    class="form-control"
                                    placeholder="Your Name"
                                    required
                                />
                            </p>
                        </div>
                        <div>
                            <p>Enter your email
                                <input
                                    v-model="newComment.email"
                                    class="form-control"
                                    placeholder="Your Email"
                                    required
                                />
                            </p>
                        </div>
                        <froala :tag="'textarea'" :config="config" v-model:value="newComment.text">Init text</froala>
                        <div class="mar-top clearfix">
                            <button class="btn btn-sm btn-primary" type="submit">Add Comment</button>
                            <div class="pull-right">
                                <p>Sort By
                                    <select
                                        class="sort pull-right"
                                        v-model="sort_option"
                                    >
                                        <option value="name-asc" selected>Name (А-Z)</option>
                                        <option value="name-desc">Name (Z-А)</option>
                                        <option value="email-asc">Email (А-Z)</option>
                                        <option value="email-desc">Email (Z-А)</option>
                                        <option value="created_at-asc">Time (А-Z)</option>
                                        <option value="created_at-desc">Time (Z-А)</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel">
                <div
                    class="panel-body"
                    v-for="comment in comments"
                    :key="comment.id"
                >
                    <div class="media-block">
                        <a class="media-left" href="#">
                            <img class="img-circle img-sm" alt="Profile Picture" src="https://bootdey.com/img/Content/avatar/avatar1.png">
                        </a>
                        <div class="media-body">
                            <div class="mar-btm">
                                <a href="#" class="btn-link text-semibold media-heading box-inline">
                                    {{ comment.user.email }}</a>
                                <p class="text-muted text-sm"> {{ comment.created_at }}</p>
                            </div>
                            <div v-html="comment.text"></div>
                            <div class="pad-ver">
                                <button
                                    class="btn btn-sm btn-default btn-hover-primary"
                                    @click="replyToComment(comment)"
                                >
                                    <i class="fa fa-reply"></i> Add comment
                                </button>
                                <button
                                    v-if="comment.descendants.length"
                                    @click="toggleChildComments(comment.id)"
                                    class="btn btn-sm btn-default btn-hover-primary"
                                >
                                    {{ showComments[comment.id] ? 'Hide' : 'Show' }} comments
                                </button>
                            </div>

                            <div v-if="comment.replying">
                                <div class="form">
                                    <input
                                        v-model="comment.reply_name"
                                        placeholder="Your Name"
                                    />
                                </div>
                                <div class="form">
                                    <input
                                        v-model="comment.reply_email"
                                        placeholder="Your Email"
                                    />
                                </div>
                                <div class="form">
                                    <textarea
                                    v-model="comment.reply_text"
                                    class="form-control"
                                    placeholder="Your Reply"
                                    required
                                    ></textarea>
                                </div>
                                <button @click="addReply(comment)">Add Reply</button>
                            </div>

                            <hr>
                            <div v-if="showComments[comment.id]">
                                <child-comment-component :replies="comment.descendants"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-footer">
                    <div class="text-center">
                        <ul class="pagination">
                            <li v-if="comment_page > 1" @click="prevPage">
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li v-for="page in comment_page_count" :key="page" :class="{ active: page === comment_page }" @click="changePage(page)">
                                <a href="#">{{ page }}</a>
                            </li>
                            <li v-if="comment_page < comment_page_count" @click="nextPage">
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
