import {ref, inject, watch} from "vue";
import {useRouter} from "vue-router";
import axios from "axios";

export default function useComments() {
    const comments = ref([])
    const comment = ref({})
    const router = useRouter()
    const comment_page = ref(1);
    const comment_page_count = ref(1);
    const store = inject('store');
    const sort_option = ref('title-asc');


    const changePage = async (page) => {
        comment_page.value = page;
        await getComments();
    };

    /**
     * Next page for pagination
     */
    const nextPage = async () => {
        if (comment_page.value < comment_page_count.value) {
            comment_page.value++;
            await getComments();
        }
    };

    /**
     * Preview page for pagination
     */
    const prevPage = async () => {
        if (comment_page.value > 1) {
            comment_page.value--;
            await getComments();
        }
    };

    /**
     * @returns {Promise<void>}
     */
    const getComments = async () => {
        try {
            const {data} = await axios.get('/api/comments',
                {
                    params: {
                        page: comment_page.value,
                    }
                }
            )

            comments.value = data?.data
            comment_page_count.value = data?.meta?.last_page

            return true
        } catch (err) {

            return false
        }
    }

    /**
     * @param id
     * @returns {Promise<boolean>}
     */
    const getComment = async (id) => {
        try {
            const {data} = await axios.get('/api/comments/' + id)
            comment.value = data.data

            return true
        } catch (err) {

            return false
        }
    }

    /**
     * @param data
     * @returns {Promise<boolean>}
     */
    const storeComment = async (data) => {
        let confirm_response = confirm("Are you sure you want to save this comment?");
        if (confirm_response) {
            try {
                await axios.post('/api/comments', data)

                return true
            } catch (e) {
                window.alert("Error when saving");
                console.log(e.response)
            }
        }

        return false;
    }

    /**
     * @param id
     * @returns {Promise<boolean>}
     */
    const updateComment = async (id) => {
        let confirm_response = confirm("Are you sure you want to update this comment?");
        if (confirm_response) {
            try {
                await axios.patch('/api/comments/' + id, comment.value)

                return true
            } catch (err) {

                return false
            }
        }

        return false;
    }

    /**
     * @param id
     * @returns {Promise<boolean>}
     */
    const destroyComment = async (id) => {
        if (id !== undefined) {
            let confirm_response = confirm("Are you sure you want to delete this comment?");
            if (confirm_response) {
                try {
                    await axios.delete('/api/comments/' + id)

                    return true
                } catch (err) {

                    return false
                }
            }
        }

        return false;
    }

    /**
     * Sort comments name, email and time (A-Z, Z-A)
     */
    const sortComments = () => {
        const sorted_comments = [...comments.value];
        if (sort_option.value === 'name-asc') {
            sorted_comments.sort((a, b) => a.user.name.localeCompare(b.user.name));
        } else if (sort_option.value === 'name-desc') {
            sorted_comments.sort((a, b) => b.user.name.localeCompare(a.user.name));
        } else if (sort_option.value === 'email-asc') {
            sorted_comments.sort((a, b) => a.user.email.localeCompare(b.user.email));
        } else if (sort_option.value === 'email-desc') {
            sorted_comments.sort((a, b) => b.user.email.localeCompare(a.user.email));
        } else if (sort_option.value === 'created_at-asc') {
            sorted_comments.sort((a, b) => a.created_at.localeCompare(b.created_at));
        } else if (sort_option.value === 'created_at-desc') {
            sorted_comments.sort((a, b) => b.created_at.localeCompare(a.created_at));
        }
        comments.value = sorted_comments;
    };

    watch(sort_option, () => {
        sortComments();
    });

    return {
        getComments,
        getComment,
        storeComment,
        updateComment,
        destroyComment,
        changePage,
        nextPage,
        prevPage,
        comments,
        comment,
        comment_page,
        comment_page_count,
        sort_option
    }
}
