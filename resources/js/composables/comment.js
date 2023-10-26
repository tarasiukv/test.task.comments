import {ref, reactive, inject} from "vue";
import {useRouter} from "vue-router";
import axios from "axios";

export default function useComments() {
  const comments = ref([])
  const comment = ref({})
  const router = useRouter()
  const comment_page = ref(1);
  const comment_page_count = ref(1);
  const store = inject('store')

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
      console.log("GetComments")
      const {data} = await axios.get('/api/comments',
        {
          params: {
            page: comment_page.value,
          }
        }
      )

      comments.value = data?.data
      comment_page_count.value = data?.meta?.last_comment_page

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

  return {
    getComments,
    getComment,
    storeComment,
    updateComment,
    destroyComment,
    nextPage,
    prevPage,
    comments,
    comment,
    comment_page,
    comment_page_count,
  }
}
