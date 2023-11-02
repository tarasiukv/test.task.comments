<script setup>
import {ref, onMounted, watch} from "vue";
import useComments from "../composables/comment.js";
import ChildCommentComponent from "./ChildCommentComponent.vue";
import CaptchaCode from "vue-captcha-code";
import useCaptcha from "../composables/captcha.js";
import useAddingComments from "../composables/addingComment.js";

const {
    comments,
    comment_page,
    comment_page_count,
    sort_option,
    getComments,
    nextPage,
    prevPage,
    changePage
} = useComments();

const {
    code,
    inputCode,
    shouldRefreshCaptcha,
    handleConfirm,
    handleRefreshCaptcha,
} = useCaptcha();

const {
    config,
    new_comment,
    showComments,
    replyToComment,
    getFile,
    addNewComment,
    addReply,
    toggleChildComments,
} = useAddingComments();


const isImage = (file_path) => {
    const extension = file_path.split('.').pop().toLowerCase();
    return ['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(extension);
};

const getFileName = (file_path) => {
    if (typeof file_path === 'string') {
        return file_path.split('/').pop();
    } else {
        return 'Невірний формат файла';
    }
};

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
                    <form action="/upload" method="POST" enctype="multipart/form-data">
                        <div>
                            <p>Enter your name
                                <input
                                    v-model="new_comment.name"
                                    class="form-control"
                                    placeholder="Your Name"
                                    required
                                />
                            </p>
                        </div>
                        <div>
                            <p>Enter your email
                                <input
                                    v-model="new_comment.email"
                                    class="form-control"
                                    placeholder="Your Email"
                                    required
                                />
                            </p>
                        </div>
                        <div>
                            <p>Upload a file
                                <input
                                    type="file"
                                    @change="getFile"
                                    accept=".txt, image/png, image/jpeg, application/pdf"
                                    multiple
                                />
                            </p>
                        </div>
                        <div v-if="new_comment.image">
                            <img :src="new_comment.image" alt="Uploaded Image"/>
                        </div>
                        <froala
                            :tag="'textarea'"
                            :config="config"
                            v-model:value="new_comment.text"
                        >
                            Init text
                        </froala>
                        <div class="mar-top clearfix">
                            <div class="captcha">
                                <input v-model="inputCode" placeholder="Please Input"/>
                                <br/><br/>
                                <captcha-code
                                    :captcha="code"
                                    @update:captcha="code = $event"
                                    ref="captcha"
                                >
                                </captcha-code>
                                <p>Click on the captcha to update it</p>
                            </div>
                            <button
                                class="btn btn-sm btn-primary"
                                type="button"
                                @click.prevent="handleConfirm"
                            >
                                Add Comment
                            </button>
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
                        <div class="media-body">
                            <div class="mar-btm">
                                <a href="#">
                                    <img class="img-circle img-sm" alt="Profile Picture" src="https://bootdey.com/img/Content/avatar/avatar1.png">
                                </a>
                                <a href="#" class="btn-link text-semibold media-heading box-inline">
                                    {{ comment.user.email }}
                                </a>
                            </div>
                            <div class="mar-btm">
                                <p class="text-muted text-sm">{{ comment.created_at }}</p>
                            </div>
                            <div v-if="comment.files">
                                <div
                                    v-for="file in comment.files"
                                    :key="file"
                                >
                                    <a :href="'/storage/app/public/' + file.file_path" target="_blank">
                                        <img v-if="isImage(file.file_path)" :src="'/storage/' + file.file_path" alt="Image" />
                                        <a
                                            v-else
                                            :href="'/storage/' + file.file_path"
                                            target="_blank"
                                        >
                                            {{ getFileName(file.file_path) }}
                                        </a>
                                    </a>
                                </div>
                            </div>
                            <div v-html="comment.text"></div>
                            <div class="pad-ver">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <button class="btn btn-sm btn-default btn-hover-primary" @click="replyToComment(comment)">
                                        <i class="fa fa-reply"></i> Add comment
                                    </button>
                                    <div style="width: 10px;"></div>
                                    <button
                                        class="btn btn-sm btn-default btn-hover-primary"
                                        v-if="comment.descendants.length"
                                        @click="toggleChildComments(comment.id)"
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
                        <li v-for="page in comment_page_count" :key="page" :class="{ active: page === comment_page }"
                            @click="changePage(page)">
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
</template>

<style scoped>
.captcha {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
    margin-top: 10px;
}
</style>
