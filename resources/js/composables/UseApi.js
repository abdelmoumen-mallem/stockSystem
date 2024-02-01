import axios from "axios";

const axiosInstance = axios.create({
    baseURL: `${window.location.protocol}//${window.location.host}/api/`,
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

export default function UseApi() {
    
    const $get = (url, params = {}) => axiosInstance.get(url, params).then((response) => response.data)
    
    const $post = (url, params = {}) => axiosInstance.post(url, params).then((response) => response.data)

    const $put = (url, params = {}) => axiosInstance.put(url, params).then((response) => response.data)

    const $delete = (url, params = {}) => axiosInstance.delete(url, params).then((response) => response.data)

    return {
        $get, $post, $put, $delete
    }
}
