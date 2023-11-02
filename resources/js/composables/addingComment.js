import {ref,} from "vue";
import useComments from "./comment.js";

export default function useAddingComments() {
    const new_comment = ref({
        name: "",
        email: "",
        text: "",
    });
    const showComments = ref({});
    const files = ref('');
    const { storeComment } = useComments();


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

    const addNewComment = async () => {
        if (new_comment.value.name && new_comment.value.email && new_comment.value.text) {
            let formData = await makeFormData();
            if (await storeComment(formData)) {
                new_comment.value.name = "";
                new_comment.value.email = "";
                new_comment.value.text = "";
                new_comment.value.file = null;
            } else {
            }
        }
    };

    const config = ref({
        placeholderText: 'Edit Your Content Here!',
        charCounterCount: false,
        imageUploadURL: '/comments',
        fileUploadURL: '/comments',
    })


    const makeFormData = async () => {
        const formData = new FormData();

        formData.append('name', new_comment.value.name);
        formData.append('email', new_comment.value.email);
        formData.append('text', new_comment.value.text);

        for (let i = 0; i < files.value.length; i++) {
            const file = files.value[i];
            formData.append('files[]', file, file.name);
        }

        return formData
    }

    const getFile = (event) => {
        files.value = event.target.files;
    };

    return {
        replyToComment,
        getFile,
        addNewComment,
        addReply,
        toggleChildComments,
        config,
        new_comment,
        showComments,
    }
}
