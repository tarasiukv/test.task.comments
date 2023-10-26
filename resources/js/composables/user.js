import {ref, reactive, inject} from "vue";
import {useRouter} from "vue-router";
import {debounce} from "lodash";
import axios from "axios";

export default function useUsers() {
  const users = ref([])
  const user = ref({})
  const router = useRouter()
  const user_page = ref(1);
  const user_page_count = ref(1);
  const store = inject('store')

  /**
   * Next page for pagination
   */
  const nextPage = async () => {
    if (user_page.value < user_page_count.value) {
      user_page.value++;
      await getUsers();
    }
  };

  /**
   * Preview page for pagination
   */
  const prevPage = async () => {
    if (user_page.value > 1) {
      user_page.value--;
      await getUsers();
    }
  };

  /**
   * @returns {Promise<void>}
   */
  const getUsers = async () => {
    try {
      console.log("GetUsers")
      const {data} = await axios.get('/api/users',
        {
          params: {
            page: user_page.value,
          }
        }
      )

      users.value = data?.data
      user_page_count.value = data?.meta?.last_user_page

      return true
    } catch (err) {

      return false
    }
  }

  /**
   * @param id
   * @returns {Promise<boolean>}
   */
  const getUser = async (id) => {
    try {
      const {data} = await axios.get('/api/users/' + id)
      user.value = data.data

      return true
    } catch (err) {

      return false
    }
  }

  /**
   * @param data
   * @returns {Promise<boolean>}
   */
  const storeUser = async (data) => {
    let confirm_response = confirm("Are you sure you want to save this user?");
    if (confirm_response) {
      try {
        await axios.post('/api/register', data)

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
  const updateUser = async (id) => {
    let confirm_response = confirm("Are you sure you want to update this user?");
    if (confirm_response) {
      try {
        await axios.patch('/api/users/' + id, user.value)

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
  const destroyUser = async (id) => {
    if (id !== undefined) {
      let confirm_response = confirm("Are you sure you want to delete this user?");
      if (confirm_response) {
        try {
          await axios.delete('/api/users/' + id)

          return true
        } catch (err) {

          return false
        }
      }
    }

    return false;
  }

  return {
    getUsers,
    getUser,
    storeUser,
    updateUser,
    destroyUser,
    nextPage,
    prevPage,
    users,
    user,
    user_page,
    user_page_count,
  }
}
