import {ref} from "vue";
import useAddingComments from "./addingComment.js";


export default function useCaptcha() {
    const code = ref("");
    const inputCode = ref("");
    const shouldRefreshCaptcha = ref(false);
    const {addNewComment} = useAddingComments();

    const handleConfirm = async () => {
        if (code.value === inputCode.value) {
            alert("Matched");
            await addNewComment();
            code.value = "";
            shouldRefreshCaptcha.value = true;
        } else {
            alert("Not Matched");
        }
    };
    const handleRefreshCaptcha = () => {
        if ($refs.captcha) {
            $refs.captcha.refreshCaptcha();
        }
    }


    return {
        handleConfirm,
        handleRefreshCaptcha,
        code,
        inputCode,
        shouldRefreshCaptcha,
    }
}
