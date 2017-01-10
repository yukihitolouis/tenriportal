import axios from "axios";

export function fetchPosts() {
  return function(dispatch) {
    //axios.get("http://localhost/react_test/wp-json/wp/v2/posts")
    axios.get("http://localhost/react_test/wp-json/acf/v2/post/22/")
      .then((response) => {
        console.log('response.data', response.data);
        dispatch({type: "FETCH_POSTS_FULFILLED", payload: response.data})
      })
      .catch((err) => {
        dispatch({type: "FETCH_POSTS_REJECTED", payload: err})
      })
  }
}

export function addPost(id, text) {
  return {
    type: 'ADD_POST',
    payload: {
      id,
      text,
    },
  }
}

export function updatePost(id, text) {
  return {
    type: 'UPDATE_POST',
    payload: {
      id,
      text,
    },
  }
}

export function deletePost(id) {
  return { type: 'DELETE_POST', payload: id}
}
